const Juego = (() => {
    let sumaCrupier = 0;
    let sumaJugador = 0;
    let contadorAsCrupier = 0;
    let contadorAsJugador = 0;
    let cartaOculta;
    let mazo = [];
    let puedePedir = true;
    let banca = 1000;
    let apuesta = 0;
    let apuestaModal = document.getElementById("modal-overlay");
    let ganadas = 0;
    let perdidas = 0;
    let empates = 0;
    let apuestasTotales = 0;
    let apuestasGanadas = 0;
    let apuestasPerdidas = 0;

    //apuesta minima
    //doblar apuesta
    //icono 
    let partidasJugadas = 0;
    const MAX_PARTIDAS = 5;

    const VALORES = ["A", "2", "3", "4", "5", "6", "7", "8", "9", "10", "J", "Q", "K"];
    const TIPOS = ["C", "D", "H", "S"];

    const inicializar = () => {
        construirMazo();
        barajarMazo();
        hacerApuesta()
    };

    const hacerApuesta = () => {
        if (partidasJugadas >= MAX_PARTIDAS) {
            mostrarPantallaResultado("¡Has alcanzado el límite de manos por partida!");
            return;
        }

        apuestaModal.classList.remove("hidden");
        const bancaSpan = document.getElementById("banca");
        const botonApuesta = document.getElementById("apostar");
        const inputApuesta = document.getElementById("apuesta");

        bancaSpan.innerText = banca;

        botonApuesta.replaceWith(botonApuesta.cloneNode(true));
        const nuevoBotonApuesta = document.getElementById("apostar");

        nuevoBotonApuesta.addEventListener("click", () => {
            const apuestaIngresada = parseInt(inputApuesta.value);

            if (isNaN(apuestaIngresada) || apuestaIngresada <= 0) {
                alert("Ingresa una apuesta válida.");
                return;
            }

            if (apuestaIngresada > banca) {
                alert("No tienes suficiente dinero.");
                return;
            }

            apuesta = apuestaIngresada;
            banca -= apuesta;
            apuestaModal.classList.add("hidden");

            iniciarJuego(); 
        });
    };

    document.getElementById("cobrar-salir").addEventListener("click", () => {
            fetch('/guardar-partida', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                partidas_jugadas: partidasJugadas,
                ganadas: ganadas,
                perdidas: perdidas,
                empates: empates,
                banca_final: banca,
                apuestas_totales: apuestasTotales,
                apuestas_ganadas: apuestasGanadas,
                apuestas_perdidas: apuestasPerdidas
            })
        }).then(() => {
            console.log("Partida guardada exitosamente.");
            window.location.href = '/';
        }).catch((error) => {
            console.error("Error al guardar la partida:", error);
        });
    });

    const construirMazo = () => {
        for (let tipo of TIPOS) {
            for (let valor of VALORES) {
                mazo.push(`${valor}-${tipo}`);
            }
        }
    };

    const barajarMazo = () => {
        for (let i = mazo.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [mazo[i], mazo[j]] = [mazo[j], mazo[i]];
        }
    };

    const iniciarJuego = () => {
        partidasJugadas++;
        resetearJuego();
        cartaOculta = mazo.pop();
        sumaCrupier += obtenerValor(cartaOculta);
        contadorAsCrupier += esAs(cartaOculta);

        while (sumaCrupier < 17) {
            repartirCarta("dealer-cards", true);
        }

        for (let i = 0; i < 2; i++) {
            repartirCarta("your-cards", false);
        }

        document.getElementById("hit").addEventListener("click", pedirCarta);
        document.getElementById("stay").addEventListener("click", plantarse);
    };

    const resetearJuego = () => {
        sumaCrupier = 0;
        sumaJugador = 0;
        contadorAsCrupier = 0;
        contadorAsJugador = 0;
        puedePedir = true;
        document.getElementById("dealer-cards").innerHTML = `<img src="${cardsUrl}BACK.png" alt="Carta Oculta" id="hidden" class="w-16 h-24 rounded-lg">`;
        document.getElementById("your-cards").innerHTML = "";
        document.getElementById("results").innerText = "";
        document.getElementById("dealer-sum").innerText = "";
        document.getElementById("your-sum").innerText = "";
        document.getElementById("game-container").style.display = "block";
        document.getElementById("resultado-final").style.display = "none";
    };

    const repartirCarta = (contenedor, esCrupier) => {
        const carta = mazo.pop();
        const valor = obtenerValor(carta);
        const esUnAs = esAs(carta);

        if (esCrupier) {
            sumaCrupier += valor;
            contadorAsCrupier += esUnAs;
        } else {
            sumaJugador += valor;
            contadorAsJugador += esUnAs;
        }

        mostrarCarta(carta, contenedor);
        actualizarSumas();
    };

    const pedirCarta = () => {
        if (!puedePedir) return;

        repartirCarta("your-cards", false);

        if (reducirAs(sumaJugador, contadorAsJugador) > 21) {
            puedePedir = false;
            plantarse();
        }
    };

    const plantarse = () => {
        puedePedir = false;
        mostrarCartaOculta();
        setTimeout(determinarResultado, 1000);
    };

    const mostrarCartaOculta = () => {
        const image = document.getElementById("hidden");
        image.classList.add("flip-anim");

        setTimeout(() => {
            image.src = obtenerImagenCarta(cartaOculta);
        }, 300); 
        setTimeout(() => {
            image.classList.remove("flip-anim");
        }, 600);
    };


    const determinarResultado = () => {
        sumaCrupier = reducirAs(sumaCrupier, contadorAsCrupier);
        sumaJugador = reducirAs(sumaJugador, contadorAsJugador);

        let mensaje = "";
        if (sumaJugador > 21) {
            mensaje = "¡Perdiste!";
            perdidas++;
            apuestasPerdidas += apuesta;
        } else if (sumaCrupier > 21) {
            mensaje = "¡Ganaste!";
            ganadas++;
            apuestasGanadas += apuesta * 2;
            banca += apuesta * 2;
        } else if (sumaJugador === sumaCrupier) {
            mensaje = "¡Empate!";
            empates++;
            banca += apuesta;
        } else if (sumaJugador > sumaCrupier) {
            mensaje = "¡Ganaste!";
            ganadas++;
            apuestasGanadas += apuesta * 2;
            banca += apuesta * 2;
        } else {
            mensaje = "¡Perdiste!";
            perdidas++;
            apuestasPerdidas += apuesta;
        }

        apuestasTotales += apuesta;
        mostrarPantallaResultado(mensaje)
        apuesta = 0;
    };

    const mostrarPantallaResultado = (mensaje) => {
        document.getElementById("game-container").style.display = "none";
        document.getElementById("resultado-final").style.display = "block";
        document.getElementById("resultado-mensaje").innerText = mensaje;
        document.getElementById("dealer-sum-final").innerText = sumaCrupier;
        document.getElementById("your-sum-final").innerText = sumaJugador;
        document.getElementById("manos-restantes-final").innerText = `Te quedan ${MAX_PARTIDAS - partidasJugadas} mano(s)`;
        document.getElementById("banca-actual-final").innerText = `Banca actual: $${banca}`;
    };

    const mostrarCarta = (carta, contenedor) => {
        const imagenCarta = document.createElement("img");
        imagenCarta.src = obtenerImagenCarta(carta);
        imagenCarta.classList.add("w-16", "h-24", "rounded-lg");
        document.getElementById(contenedor).appendChild(imagenCarta);
    };

    const obtenerImagenCarta = (carta) => {
        return cardsUrl + carta + ".png";
    };

    const obtenerValor = (carta) => {
        const valor = carta.split("-")[0];
        return isNaN(valor) ? (valor === "A" ? 11 : 10) : parseInt(valor);
    };

    const esAs = (carta) => {
        return carta[0] === "A" ? 1 : 0;
    };

    const reducirAs = (suma, contadorAs) => {
        while (suma > 21 && contadorAs > 0) {
            suma -= 10;
            contadorAs -= 1;
        }
        return suma;
    };

    const actualizarSumas = () => {
        const sumaVisibleCrupier = sumaCrupier - obtenerValor(cartaOculta);
        const sumaCrupierTexto = puedePedir ? `${sumaVisibleCrupier} + ?` : reducirAs(sumaCrupier, contadorAsCrupier);

        document.getElementById("dealer-sum").innerText = `${sumaCrupierTexto}`;
        document.getElementById("your-sum").innerText = `${reducirAs(sumaJugador, contadorAsJugador)}`;
    };


    return {
        inicializar,
    };
})();

window.onload = Juego.inicializar;
