const paises = [
  { code: "AR", name: "Argentina", icon: "argentina.png", flag: "🇦🇷" },
  { code: "BO", name: "Bolivia", icon: "bolivia.png", flag: "🇧🇴" },
  { code: "BR", name: "Brasil", icon: "brasil.png", flag: "🇧🇷" },
  { code: "CL", name: "Chile", icon: "chile.png", flag: "🇨🇱" },
  { code: "CO", name: "Colombia", icon: "colombia.png", flag: "🇨🇴" },
  { code: "CR", name: "Costa Rica", icon: "costarica.png", flag: "🇨🇷" },
  { code: "CU", name: "Cuba", icon: "cuba.png", flag: "🇨🇺" },
  { code: "DO", name: "República Dominicana", icon: "dominicana.png", flag: "🇩🇴" },
  { code: "EC", name: "Ecuador", icon: "ecuador.png", flag: "🇪🇨" },
  { code: "SV", name: "El Salvador", icon: "elsalvador.png", flag: "🇸🇻" },
  { code: "ES", name: "España", icon: "espana.png", flag: "🇪🇸" },
  { code: "GT", name: "Guatemala", icon: "guatemala.png", flag: "🇬🇹" },
  { code: "HN", name: "Honduras", icon: "honduras.png", flag: "🇭🇳" },
  { code: "MX", name: "México", icon: "mexico.png", flag: "🇲🇽" },
  { code: "NI", name: "Nicaragua", icon: "nicaragua.png", flag: "🇳🇮" },
  { code: "PA", name: "Panamá", icon: "panama.png", flag: "🇵🇦" },
  { code: "PY", name: "Paraguay", icon: "paraguay.png", flag: "🇵🇾" },
  { code: "PE", name: "Perú", icon: "peru.png", flag: "🇵🇪" },
  { code: "PT", name: "Portugal", icon: "portugal.png", flag: "🇵🇹" },
  { code: "PR", name: "Puerto Rico", icon: "puertorico.png", flag: "🇵🇷" },
  { code: "UY", name: "Uruguay", icon: "uruguay.png", flag: "🇺🇾" },
  { code: "VE", name: "Venezuela", icon: "venezuela.png", flag: "🇻🇪" },
  { code: "AD", name: "Andorra", icon: "andorra.png", flag: "🇦🇩" },
  { code: "GQ", name: "Guinea Ecuatorial", icon: "guineaecuatorial.png", flag: "🇬🇶" }
];

const RciPaises = new function () {

  /**
   * Obtener un campo (por defecto: name) a partir del código ISO
   * @param {string} code - Código ISO del país
   * @param {string} field - Campo a retornar (name, icon, flag, etc.)
   * @returns {string}
   */
  this.codeTo = function (code, field = 'name') {
    const pais = paises.find(p => p.code === code?.toUpperCase());
    return pais ? (pais[field] || pais.name) : 'ND';
  };

  /**
   * Retorna la URL al ícono PNG oficial de bandera
   * @param {string} code - Código ISO del país
   * @returns {string}
   */
  this.flagIconUrl = function (code) {
    return code
      ? `https://flagcdn.com/w20/${code.toLowerCase()}.png`
      : '';
  };

};
