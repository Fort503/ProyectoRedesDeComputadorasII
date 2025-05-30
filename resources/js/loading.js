console.log('[Loading.js] cargado');

window.addEventListener("load", () => {
  console.log('[Loading.js] window.onload disparado');

  const loader = document.getElementById("loading-screen");
  if (loader) loader.classList.add("hidden");

  const contenido = document.getElementById("contenido");
  if (contenido) contenido.classList.remove("hidden");

  console.log('[Loading.js] loading ocultado y contenido mostrado');
});
