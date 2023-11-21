<style>
    .pai-content{
        width: 100%;
        height: calc(100vh - 180px);
        border: 1px solid #FAFAFA;
    }
</style>

<ul class="nav nav-pills mb-2 justify-content-center" id="myTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#pai-investigaciones" type="button" role="tab" aria-controls="pai-investigaciones" aria-selected="true">Investigaciones</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#pai-tablero" type="button" role="tab" aria-controls="pai-tablero" aria-selected="false">Resumen</button>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade show active" id="pai-investigaciones" role="tabpanel" aria-labelledby="home-tab">
      <iframe class="pai-content" src="https://observatoriocultura.github.io/observatorio2023/" frameborder="0"></iframe>
  </div>
  <div class="tab-pane fade" id="pai-tablero" role="tabpanel" aria-labelledby="pai-tablero">
    <div class="container">
        <iframe class="pai-content" src="https://lookerstudio.google.com/embed/reporting/6f953cd3-0c43-4aa8-8a83-19815aaa0240/page/p_bwyo0a546c" frameborder="0"></iframe>
    </div>
  </div>
</div>
