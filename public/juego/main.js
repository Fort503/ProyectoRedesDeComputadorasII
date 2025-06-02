const Juego = (() => {
    // ─── ESTADO DEL JUEGO ─────────────────────────────────────────────────────
    let mazo = [];
    let cartaOculta;
    let sumaCrupier = 0, sumaJugador = 0;
    let contadorAsCrupier = 0, contadorAsJugador = 0;
    let puedePedir = true;
    let betsBound = false; 

    // ─── ESTADO DE APUESTAS ────────────────────────────────────────────────────
    let banca = 1000;
    let apuesta = 0;
    let apuestaSum = 0;
    let betChips = [];
    let puedeDoblar = false;

    // ─── ESTADÍSTICAS ───────────────────────────────────────────────────────────
    let ganadas = 0, perdidas = 0, empates = 0;
    let apuestasTotales = 0, apuestasGanadas = 0, apuestasPerdidas = 0;
    let partidasJugadas = 0;
    const MAX_PARTIDAS = 5;

    // ─── CONSTANTES ─────────────────────────────────────────────────────────────
    const VALORES     = ["A","2","3","4","5","6","7","8","9","10","J","Q","K"];
    const TIPOS       = ["C","D","H","S"];
    const CHIP_VALUES = [5,10,20,50,100,200,500,1000,2000];

    // ─── RUTAS PASADAS DESDE BLADE ────────────────────────────────────────────
    const cardsUrl = window.cardsUrl; 
    const chipsUrl = window.chipsUrl;

    // ─── INICIALIZACIÓN ────────────────────────────────────────────────────────
    function inicializar() {
        apuestaSum = 0;
        betChips  = [];
        renderBetChips();       
        updateBetButtons(); 
        const action = document.getElementById('action-buttons');
        action.classList.add('hidden');
        action.classList.remove('flex');

        const btnDoblar = document.getElementById('btn-double-bet');
        btnDoblar.disabled = true;
        construirMazo();
        barajarMazo();
        renderAvailableChips();
        mostrarPanelApuestas();
    }

    // ─── FUNCIONES DE MAZO ─────────────────────────────────────────────────────
    function construirMazo() {
        mazo = [];
        TIPOS.forEach(tipo => {
        VALORES.forEach(valor => {
            mazo.push(`${valor}-${tipo}`);
        });
        });
    }
    function barajarMazo() {
        for (let i = mazo.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [mazo[i], mazo[j]] = [mazo[j], mazo[i]];
        }
    }

    // ─── PANEL DE APUESTAS ─────────────────────────────────────────────────────
    function mostrarPanelApuestas() {
    // Actualiza banca y genera las fichas disponibles
    document.getElementById('panel-banca').innerText = banca;
    renderAvailableChips();

    // Sólo la primera vez enlazamos los listeners
    if (!betsBound) {
        // Confirmar apuesta
        document.getElementById('btn-confirm-bet')
        .addEventListener('click', confirmarApuesta);

        // Doblar apuesta
        document.getElementById('btn-double-bet')
        .addEventListener('click', doblarApuesta);

        // Área de drop de fichas
        const betArea = document.getElementById('chips-bet');
        betArea.addEventListener('dragover', e => e.preventDefault());
        betArea.addEventListener('drop', e => {
        e.preventDefault();
        const v   = parseInt(e.dataTransfer.getData('value'), 10);
        addChipToBet(v, `${chipsUrl}${v}.png`);
        });

        betsBound = true;
    }
    }

    function renderAvailableChips() {
    const container = document.getElementById('chips-available');
    container.innerHTML = '';

    CHIP_VALUES
        .filter(v => v <= banca)
        .forEach(v => {
        const img = document.createElement('img');
        img.src           = `${chipsUrl}${v}.png`;
        img.dataset.value = v;
        img.className     = 'chip w-12 h-12 cursor-pointer hover:scale-110 transition-transform';
        img.draggable     = true;

        // Drag Start
        img.addEventListener('dragstart', e => {
            e.dataTransfer.setData('value', v);
        });
        // Click para añadir
        img.addEventListener('click', () => addChipToBet(v, img.src));

        container.appendChild(img);
        });

    document.getElementById('panel-banca').innerText = banca;
    document.getElementById('panel-banca-2').innerText = banca;
    }

    function addChipToBet(value, src) {
        if (apuestaSum + value > banca) return alert('Fondos insuficientes');
        apuestaSum += value;
        betChips.push(value);
        renderBetChips();
        updateBetButtons();
    }

    function renderBetChips() {
        const betArea = document.getElementById('chips-bet');
        betArea.innerHTML = '';
        if (betChips.length === 0) {
        betArea.innerHTML = '<span class="text-green-300 italic">Sin apuesta</span>';
        } else {
        betChips.forEach((v, i) => {
            const img = document.createElement('img');
            img.src = `${chipsUrl}${v}.png`;
            img.className = 'w-10 h-10 cursor-pointer';
            img.addEventListener('dblclick', () => removeChipFromBet(i));
            betArea.appendChild(img);
        });
        }
    }

    function removeChipFromBet(idx) {
        const val = betChips.splice(idx, 1)[0];
        apuestaSum -= val;
        renderBetChips();
        updateBetButtons();
    }

    function updateBetButtons() {
        document.getElementById('btn-confirm-bet').disabled = (apuestaSum === 0);
        document.getElementById('btn-double-bet').disabled = !(puedeDoblar && apuestaSum > 0);
    }

    function confirmarApuesta() {
        apuesta = apuestaSum;
        banca -= apuesta;
        document.getElementById('bet-panel').classList.add('hidden');
        renderAvailableChips();
        const actionButtons = document.getElementById('action-buttons');
        actionButtons.classList.remove('hidden');
        actionButtons.classList.add('flex', 'justify-center', 'gap-4', 'mt-6');

        iniciarRonda();
    }

    function doblarApuesta() {
        if (!puedeDoblar || banca < apuesta) return alert('No puedes doblar');
        banca -= apuesta;
        apuesta *= 2;
        apuestaSum = apuesta;
        renderBetChips();
        renderAvailableChips();
        updateBetButtons();
        puedeDoblar = false;
    }

    // ─── RONDA ──────────────────────────────────────────────────────────────────
    function iniciarRonda() {
        partidasJugadas++;
        resetearMesa();

        cartaOculta = mazo.pop();
        sumaCrupier += valorCarta(cartaOculta);
        contadorAsCrupier += esAs(cartaOculta);

        while (sumaCrupier < 17) repartirCarta('dealer-cards', true);

        for (let i = 0; i < 2; i++) repartirCarta('your-cards', false);

        document.getElementById('hit').addEventListener('click', () => {
        pedirCarta();
        // Al pedir cualquier carta, ya no se puede doblar
        document.getElementById('btn-double-bet').classList.add('hidden');
        puedeDoblar = false;
        });
        document.getElementById('stay').addEventListener('click', plantarse);

        puedeDoblar = true;
        updateBetButtons();
    }

    function resetearMesa() {
        sumaCrupier = sumaJugador = 0;
        contadorAsCrupier = contadorAsJugador = 0;
        puedePedir = true;
        document.getElementById('dealer-cards').innerHTML =
        `<img src="${cardsUrl}BACK.png" id="hidden" class="w-16 h-24 rounded-lg">`;
        document.getElementById('your-cards').innerHTML = '';
        document.getElementById('results').innerText = '';
        document.getElementById('dealer-sum').innerText = '';
        document.getElementById('your-sum').innerText = '';
    }

    function repartirCarta(contenedor, esCrupier) {
        const carta = mazo.pop();
        const valor = valorCarta(carta), isAs = esAs(carta);
        if (esCrupier) {
        sumaCrupier += valor; contadorAsCrupier += isAs;
        } else {
        sumaJugador += valor; contadorAsJugador += isAs;
        }
        mostrarCarta(carta, contenedor);
        actualizarSumas();
    }

    // ─── PEDIR / PLANTARSE ──────────────────────────────────────────────────────
    function pedirCarta() {
        if (!puedePedir) return;
        repartirCarta('your-cards', false);
        if (ajustarAs(sumaJugador, contadorAsJugador) > 21) {
        puedePedir = false;
        plantarse();
        }
    }

    function plantarse() {
        puedePedir = false;
        mostrarOculta();
        setTimeout(determinarResultado, 500);
    }

    function mostrarOculta() {
        const img = document.getElementById('hidden');
        img.classList.add('flip-anim');
        setTimeout(() => img.src = valorImagen(cartaOculta), 300);
        setTimeout(() => img.classList.remove('flip-anim'), 600);
    }

    // ─── RESOLUCIÓN ─────────────────────────────────────────────────────────────
    function determinarResultado() {
        sumaCrupier = ajustarAs(sumaCrupier, contadorAsCrupier);
        sumaJugador = ajustarAs(sumaJugador, contadorAsJugador);
        let msg;

        if (sumaJugador > 21) {
        msg = '¡Perdiste!'; perdidas++; apuestasPerdidas += apuesta;
        } else if (sumaCrupier > 21) {
        msg = '¡Ganaste!'; ganadas++; apuestasGanadas += apuesta * 2; banca += apuesta * 2;
        } else if (sumaJugador === sumaCrupier) {
        msg = '¡Empate!'; empates++; banca += apuesta;
        } else if (sumaJugador > sumaCrupier) {
        msg = '¡Ganaste!'; ganadas++; apuestasGanadas += apuesta * 2; banca += apuesta * 2;
        } else {
        msg = '¡Perdiste!'; perdidas++; apuestasPerdidas += apuesta;
        }

        apuestasTotales += apuesta;
        apuesta = 0;
        mostrarResultadoFinal(msg);
    }

    function mostrarResultadoFinal(mensaje) {
        document.getElementById('game-container').style.display = 'none';
        document.getElementById('resultado-final').style.display = 'block';
        document.getElementById('resultado-mensaje').innerText = mensaje;
        document.getElementById('dealer-sum-final').innerText = sumaCrupier;
        document.getElementById('your-sum-final').innerText = sumaJugador;
        document.getElementById('manos-restantes-final').innerText =
        `Te quedan ${MAX_PARTIDAS - partidasJugadas} mano(s)`;
        document.getElementById('banca-actual-final').innerText =
        `Banca actual: $${banca}`;
    }

    // ─── UTILIDADES ─────────────────────────────────────────────────────────────
    function valorCarta(c) {
        const v = c.split('-')[0];
        return isNaN(v) ? (v === 'A' ? 11 : 10) : parseInt(v);
    }
    function esAs(c) {
        return c[0] === 'A' ? 1 : 0;
    }
    function ajustarAs(suma, cnt) {
        while (suma > 21 && cnt > 0) { suma -= 10; cnt--; }
        return suma;
    }

    function actualizarSumas() {
        const visible = sumaCrupier - valorCarta(cartaOculta);
        const textoCrupier = puedePedir
        ? `${visible} + ?`
        : ajustarAs(sumaCrupier, contadorAsCrupier);
        document.getElementById('dealer-sum').innerText = textoCrupier;
        document.getElementById('your-sum').innerText =
        ajustarAs(sumaJugador, contadorAsJugador);
    }

    function mostrarCarta(c, cont) {
        const contDiv = document.getElementById(cont);
        const img = document.createElement('img');
        img.src = valorImagen(c);
        img.classList.add("w-16", "h-24", "rounded-lg");
        if (contDiv.children.length > 0) img.style.marginLeft = '-5rem';
        contDiv.appendChild(img);
    }
    function valorImagen(carta) {
        return `${cardsUrl}${carta}.png`;
    }
    function reiniciarUI() {
    // Limpia cartas, sumas, resultados, etc.
    document.getElementById('game-container').style.display = 'block';
    document.getElementById('resultado-final').style.display = 'none';
    document.getElementById('dealer-cards').innerHTML = `<img src="${cardsUrl}BACK.png" id="hidden" …>`;
    document.getElementById('your-cards').innerHTML = '';
    document.getElementById('dealer-sum').innerText = '';
    document.getElementById('your-sum').innerText = '';
    document.getElementById('results').innerText = '';

    // Y limpia apuestas
    apuestaSum = 0;
    betChips  = [];
    renderBetChips();
    updateBetButtons();
    document.getElementById('bet-panel').classList.remove('hidden');
    document.getElementById('panel-banca').innerText = banca;
    }
    // ─── EXPONER PÚBLICO ─────────────────────────────────────────────────────────
    return { inicializar, reiniciarUI };
})();

// Arranca cuando la página esté lista
window.addEventListener('load', () => {
    Juego.inicializar();

    // Conecta el botón de reinicio
    document.getElementById('btn-restart').addEventListener('click', () => {
    Juego.reiniciarUI();
    Juego.inicializar();
    });
});
