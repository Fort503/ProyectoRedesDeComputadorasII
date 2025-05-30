<div id="loading-screen" class="fixed inset-0 z-50 neon-border">
  <!-- Fondo: aquí usamos un GIF -
  <div class="absolute inset-0 bg-center bg-no-repeat bg-cover" 
    style="background-image: url('{{ asset('Videos/loading.gif') }}');">
  </div>-->
  
  <!-- Overlay semitransparente-->
  <div class="absolute inset-0 bg-black bg-opacity-50"></div>
  
  <!-- Contenido central del loading -->
  <div class="absolute inset-0 flex items-center justify-center">
    <div class="text-center">
      <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-yellow-300 mx-auto"></div>
      <p class="text-yellow-300 mt-4 text-xl font-bold">
        Cargando...
      </p>
    </div>
  </div>
</div>