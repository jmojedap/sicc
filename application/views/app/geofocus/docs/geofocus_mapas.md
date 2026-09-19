# Mapas de GeoFocus: variables y priorización

**Fecha de implementación y migración documentada:** 18 de septiembre de 2026  
**Última actualización de este documento:** 18 de septiembre de 2026

## 1. Objetivo

La implementación permite representar sobre polígonos de una capa base:

- los valores de una variable de GeoFocus;
- el resultado general de una priorización;
- una variable individual dentro de la herramienta de priorización.

El color de cada polígono se actualiza dinámicamente al cambiar la variable, el tema o la capa. La asociación se hace mediante una llave territorial y no por posición ni por igualdad entre la cantidad de valores y la cantidad de polígonos.

La solución usa únicamente los GeoJSON locales de GeoFocus. No consume mapas base, teselas ni servicios externos de Esri, OpenStreetMap u otros proveedores.

## 2. Tecnologías utilizadas

| Tecnología | Uso |
|---|---|
| PHP y CodeIgniter | Controladores, modelo, carga de datos iniciales y generación de vistas. |
| Vue 3, versión global | Estado de la interfaz, filtros, selección de variables y actualización reactiva del mapa. |
| MapLibre GL JS 2.4.0 | Renderizado WebGL, relleno de polígonos, rotación, zoom, navegación y ventanas emergentes. |
| Axios | Consulta de los valores numéricos a la API de GeoFocus. |
| Fetch API | Descarga de los archivos GeoJSON locales. |
| GeoJSON | Geometría optimizada de las capas base. |
| Bootstrap y CSS propio | Distribución de controles, paneles, leyenda y adaptación a pantallas pequeñas. |

MapLibre se carga desde `unpkg` en las vistas principales de variables y priorización. Vue y Axios son provistos por la plantilla general de la aplicación.

## 3. Arquitectura y flujo de datos

```text
Geofocus_model::capas_base()
        │ configuración: archivo, property_key, center, zoom y rotación
        ▼
Controlador app/Geofocus
        │ entrega capa, variables y catálogo de rotaciones a la vista
        ▼
Vue + MapLibre
        ├── Fetch → content/geofocus/capas_base/{archivo_mapa}
        └── Axios → api/geofocus/get_variable_valores/{campo}/{id}
                         │
                         └── gf_territorios_valor + gf_territorios
        ▼
Unión code ↔ feature.properties[property_key]
        ▼
GeoJSON enriquecido + source.setData() + expresión de color
```

La geometría y los valores se solicitan en paralelo. El GeoJSON se conserva en memoria para no descargarlo ni analizarlo de nuevo al cambiar de variable.

## 4. Configuración del modelo

Archivo: `application/models/Geofocus_model.php`.

### 4.1. `capas_base()`

Centraliza la configuración necesaria para presentar cada capa. Cada elemento contiene:

| Propiedad | Propósito |
|---|---|
| `id` | Identificador del catálogo de capas. |
| `nombre` | Nombre visible en la interfaz. |
| `key_capa` | Llave que relaciona la capa con variables, territorios y priorizaciones. |
| `cantidad_poligonos` | Cantidad informativa usada en la interfaz; no controla la unión con los valores. |
| `archivo_mapa` | Nombre del GeoJSON optimizado que carga MapLibre. |
| `property_key` | Propiedad del polígono que debe coincidir con `code` devuelto por la API. |
| `center` | Centro inicial en formato `[longitud, latitud]`. |
| `zoom` | Nivel de acercamiento inicial de MapLibre. |
| `rotacion` | Clave de la orientación predeterminada, definida en `rotaciones()`. |

Configuración vigente:

| Capa | `key_capa` | Archivo | `property_key` | Centro | Zoom | Rotación |
|---|---|---|---|---|---:|---|
| Barrios Bogotá 2025 | `sector_catastral_0526` | `sector_catastral_0526_urbano_mapa.json` | `poligono_id` | `[-74.12, 4.65]` | 11 | `transmilenio` |
| Barrios Bogotá 2023 | `barrios_planeacion_2023` | `barrios_bogota_geofocus_mapa.json` | `ID_BARRIO` | `[-74.10, 4.65]` | 10 | `vertical` |

La cantidad de elementos del archivo puede ser menor que `cantidad_poligonos` o que el número de valores de la API. El mapa filtra y representa solamente las coincidencias de llave.

### 4.2. `rotaciones()`

Devuelve el catálogo compartido por las dos herramientas. La propiedad `key` se guarda en la capa base y `value` corresponde al `bearing` de MapLibre en grados.

| Clave | Nombre | Grados |
|---|---|---:|
| `transmilenio` | Horizontal Transmilenio | 110 |
| `vertical` | Vertical | 0 |
| `horizontal-b` | Horizontal 90° | 90 |
| `autopista_norte_horizontal` | Autopista Norte Horizontal | 99 |

Si la clave configurada en la capa no existe, la aplicación intenta usar la rotación de 0° y, como último recurso, la primera opción del catálogo.

### 4.3. Otras funciones relacionadas

- `get_variables($condition)`: entrega las variables. En priorización se filtran por `estado = 1` y por el `key_capa` de la priorización.
- `getPriorizacion($priorizacionId)`: obtiene los resultados territoriales ya calculados para la tabla y para los datos iniciales de la herramienta.
- `calcularPriorizacion($settings)`: calcula y guarda el resultado consolidado por territorio en `gf_territorios_valor`.
- `normalizarValores($field, $fieldValue)`: calcula el valor normalizado utilizado por el proceso de priorización. El mapa consulta la columna `valor` resultante.

## 5. Controladores

### 5.1. Controlador de aplicación

Archivo: `application/controllers/app/Geofocus.php`.

#### `variables($key_capa, $section, $variable_id)`

Prepara la herramienta de variables y entrega a Vue:

- todas las variables;
- catálogo de estados y temas;
- capa y sección solicitadas;
- variable que debe quedar seleccionada;
- catálogo de capas mediante `capas_base()`;
- catálogo de orientaciones mediante `rotaciones()`.

La vista principal asignada es `variables/variables_v`.

#### `priorizacion($priorizacionId)`

Prepara la herramienta de priorización:

- carga la priorización con `basic()`;
- consulta únicamente variables activas de la misma `key_capa`;
- identifica en `capas_base()` la configuración correspondiente;
- entrega el catálogo de `rotaciones()`;
- carga los territorios priorizados mediante `getPriorizacion()`;
- muestra la vista `priorizacion/priorizacion_v`.

### 5.2. Controlador de API

Archivo: `application/controllers/api/Geofocus.php`.

#### `get_variable_valores($field, $fieldValue)`

Es el endpoint común para ambos mapas:

```text
GET {URL_API}geofocus/get_variable_valores/{field}/{id}
```

Solo permite los campos:

- `variable_id`, para visualizar una variable;
- `priorizacion_id`, para visualizar el resultado general de una priorización.

La consulta une `gf_territorios_valor.territorio_id` con `gf_territorios.id` y devuelve:

```json
{
  "valores": [
    {
      "code": "llave-del-poligono",
      "name": "Nombre del territorio",
      "value": 0
    }
  ],
  "summary": {
    "sum": 0,
    "avg": 0,
    "min": 0,
    "max": 0,
    "count": 0,
    "std_dev": 0
  }
}
```

Consideraciones del contrato:

- `code` proviene de `gf_territorios.poligono_id` y se fuerza a texto para evitar diferencias entre cadenas y números.
- `value` se entrega como número de punto flotante o como `null` si no es numérico.
- el valor `0` es un dato válido; no se interpreta como ausencia de información;
- no existe una dependencia con la posición de los registros ni con la igualdad de cantidades;
- un campo distinto de los dos permitidos devuelve estado HTTP 400.

## 6. Asociación entre valores y polígonos

El algoritmo de unión es compartido conceptualmente por ambos mapas:

1. Se lee `property_key` desde la configuración de la capa base.
2. Se extraen las llaves de `feature.properties[property_key]` del GeoJSON.
3. Tanto las llaves del GeoJSON como `row.code` se convierten a texto y se les eliminan espacios en los extremos.
4. Los valores de la API que no pertenecen a la geometría visible se descartan.
5. Se construye un `Map` de JavaScript indexado por la llave normalizada.
6. Cada `feature` recibe propiedades auxiliares con su valor y nombre.
7. El GeoJSON completo se envía a MapLibre mediante `source.setData()`.

Propiedades auxiliares de variables:

- `__variable_has_value`;
- `__variable_value`;
- `__variable_name`.

Propiedades auxiliares de priorización:

- `__priority_has_value`;
- `__priority_value`;
- `__priority_name`.

La bandera `*_has_value` permite distinguir correctamente `0` de `null`. Un cero recibe color según la escala; solo una llave sin valor numérico queda marcada como “Sin dato”.

## 7. Mapa de variables

### 7.1. Archivos

| Archivo | Responsabilidad |
|---|---|
| `application/views/app/geofocus/variables/variables_v.php` | Carga MapLibre 2.4.0, estilos y subvistas de la herramienta. |
| `application/views/app/geofocus/variables/variables_mapa_v.php` | Interfaz del mapa: capa, tema, variable, orientación, leyenda, estado de carga e información de la variable. |
| `application/views/app/geofocus/variables/variables_lista_v.php` | Listado con el acceso “ver en mapa” para seleccionar una variable. |
| `application/views/app/geofocus/variables/variables_vue_v.php` | Estado, filtros, consultas, asociación de datos y ciclo de vida de MapLibre. |
| `application/views/app/geofocus/variables/variables_style_v.php` | Distribución, altura del mapa, leyenda, panel lateral y estilos adaptables. |

### 7.2. Funciones principales de Vue

- `openMap()`: abre la sección y selecciona la variable indicada o la primera disponible.
- `changeMapCapa()`: cambia la capa, restablece tema, orientación, centro y zoom, y carga una variable válida.
- `changeMapTema()` y `changeMapVariable()`: actualizan los filtros y el contenido sin recargar la página.
- `getMapData()`: carga y almacena en caché el GeoJSON.
- `getVariableMapValues()`: consulta el endpoint con `variable_id`.
- `normalizeMapJoinKey()`, `mapGeometryKeys()` y `mapValues()`: normalizan, validan y filtran las llaves.
- `mapDataWithValues()`: incorpora los valores al GeoJSON.
- `initializeVariablesMap()`: crea una sola instancia de MapLibre con fondo local, controles, rotación y popup.
- `ensureMapLayers()`: crea las capas `fill` y `line`; si cambia el archivo o `property_key`, reconstruye fuente y capas. En otros cambios usa `setData()`.
- `renderVariablesMap()`: calcula la escala, actualiza los datos y modifica la expresión de color.
- `loadVariablesMap()`: hace la carga inicial.
- `updateVariablesMap()`: cambia la variable conservando la instancia del mapa.
- `bindMapInteractions()` y `showMapFeaturePopup()`: muestran nombre, valor y unidad al pasar el cursor.

Un contador incremental (`variablesMapRequestId`) invalida respuestas anteriores cuando el usuario cambia rápidamente de variable. Esto evita que una consulta tardía sobrescriba el estado más reciente y deje polígonos con datos antiguos o sin colorear.

## 8. Mapa de priorización

### 8.1. Archivos

| Archivo | Responsabilidad |
|---|---|
| `application/views/app/geofocus/priorizacion/priorizacion_v.php` | Carga MapLibre 2.4.0, estilos y secciones de la herramienta. |
| `application/views/app/geofocus/priorizacion/mapa_v.php` | Controles, mapa, leyenda y panel informativo del resultado o variable. |
| `application/views/app/geofocus/priorizacion/variables_v.php` | Permite abrir una variable seleccionada en la sección de mapa. |
| `application/views/app/geofocus/priorizacion/vue_v.php` | Alterna modos, consulta datos, asocia valores y administra MapLibre. |
| `application/views/app/geofocus/geofocus_style_v.php` | Estilos compartidos y específicos del mapa de priorización. |

La versión anterior basada en Highcharts fue reemplazada para este módulo. La implementación vigente no necesita un script independiente de Highcharts.

### 8.2. Modos de visualización

`mapMode` puede tener dos valores:

- `priorizacion`: consulta por `priorizacion_id` y usa como color principal `#AA0066`;
- `variable`: consulta por `variable_id` y usa el color configurado en la variable.

Funciones principales:

- `showPrioritizationMap()` y `actualizarMapa()`: abren el resultado consolidado.
- `setVariable()` y `actualizarCapa()`: abren una variable individual.
- `setTema()`: filtra y selecciona la primera variable activa del tema.
- `getPrioritizationMapData()`: carga y almacena en caché el GeoJSON de la capa de la priorización.
- `getPrioritizationMapValues()`: usa el mismo endpoint para cualquiera de los dos modos.
- `mapDataWithValues()`: añade las propiedades `__priority_*`.
- `initializePrioritizationMap()`: crea la instancia de MapLibre, navegación y popup.
- `ensurePrioritizationMapLayers()`: crea una vez la fuente y las capas, y después las actualiza con `setData()`.
- `renderPrioritizationMap()`: valida la llave, calcula la escala y actualiza los polígonos.
- `loadPrioritizationMap()`: resuelve el modo, el campo, el identificador y el color que deben consultarse.

También existe un contador (`priorizacionMapRequestId`) para descartar respuestas asíncronas obsoletas.

## 9. Zoom, centro y rotación

### Centro y zoom

El origen de estos valores es `Geofocus_model::capas_base()`:

```php
'center' => [-74.12, 4.65],
'zoom' => 11,
```

MapLibre espera el centro en orden `[longitud, latitud]`. Los valores de respaldo en las vistas son `[-74.10, 4.65]` y zoom `10`.

En variables, `applyMapViewForCapa()` aplica con `easeTo()` el centro, zoom y orientación cuando cambia la capa. En priorización la capa está determinada por `priorizacion.key_capa`, por lo que `mapCenter()` y `mapZoom()` se aplican durante la inicialización.

### Rotación

El selector guarda una `key` del catálogo. `selectedMapBearing()` obtiene sus grados y MapLibre los recibe en `bearing`.

- la orientación inicial se toma de `capa_base.rotacion`;
- el usuario puede cambiarla sin volver a consultar geometrías o valores;
- el cambio se anima con `easeTo({ bearing, duration: 300 })`;
- `dragRotate: true` y el control con brújula permiten rotación manual adicional;
- `pitch` permanece en `0`, por lo que no se aplica inclinación tridimensional.

## 10. Escala de color y estados visuales

La escala se calcula con los valores que sí tienen correspondencia en la geometría visible, no con valores sobrantes de la API.

- mínimo: `#f4f6f9`;
- punto intermedio al 65 %: color de la variable o de la priorización;
- máximo: versión oscurecida del color principal;
- sin dato: `#dfe4e8`;
- línea del polígono: `#59636f`, ancho `0.6` y opacidad `0.9`;
- opacidad del relleno: `0.84`.

Si mínimo y máximo son iguales, el máximo se incrementa en uno para producir una expresión de interpolación válida.

## 11. Archivos de geometría

Ubicación: `content/geofocus/capas_base/`.

| Archivo | Propósito |
|---|---|
| `barrios_bogota_geofocus_mapa.json` | Capa optimizada de Barrios Bogotá 2023. Su unión usa `ID_BARRIO`. |
| `sector_catastral_0526_urbano_mapa.json` | Alternativa urbana optimizada de la capa 2025. Su unión usa `poligono_id`. Es el archivo actualmente configurado. |
| `sector_catastral_0526_mapa.json` | Versión optimizada general de la capa sector catastral, conservada como recurso alternativo. |

Estos archivos contienen geometrías simplificadas para reducir vértices, peso de transferencia y trabajo de renderizado. Deben conservar la propiedad definida en `property_key`; eliminarla durante una nueva optimización impide asociar los valores.

## 12. Cómo agregar o ajustar una capa base

1. Generar un GeoJSON optimizado y ubicarlo en `content/geofocus/capas_base/`.
2. Confirmar que todas las entidades útiles tengan una llave territorial estable.
3. Agregar o modificar el elemento correspondiente en `Geofocus_model::capas_base()`.
4. Definir `archivo_mapa` y el nombre exacto, respetando mayúsculas, de `property_key`.
5. Ajustar `center`, `zoom` y la clave `rotacion`.
6. Verificar que `gf_territorios.poligono_id` contenga la misma representación de la llave.
7. Probar valores positivos, negativos si existen, cero, nulos y territorios sin correspondencia.

Para agregar una orientación, se añade una opción en `rotaciones()` con `key` única y `value` numérico. Después puede asignarse esa clave a cualquier capa.

## 13. Validaciones y mantenimiento

Antes de publicar cambios se recomienda verificar:

- que el GeoJSON sea un `FeatureCollection` con un arreglo `features`;
- que `property_key` exista en los polígonos;
- que la API entregue `code` como texto y `value` como número o `null`;
- que un valor `0.0000` se muestre como dato y no como “Sin dato”;
- que los registros extra de la API sean ignorados sin generar errores;
- que al cambiar rápidamente de variable permanezca visible la última selección;
- que centro, zoom y rotación predeterminados correspondan a la capa;
- que el popup no inserte HTML proveniente de nombres o unidades sin escapar;
- que los archivos optimizados no pierdan su llave territorial.

La fuente se actualiza de forma atómica con el GeoJSON enriquecido completo. No debe sustituirse este flujo por estados parciales de entidades sin revisar el manejo de cambios asíncronos, porque podría reintroducir polígonos con colores residuales al cambiar de variable.
