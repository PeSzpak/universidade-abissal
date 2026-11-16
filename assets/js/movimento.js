// Movimento da Universidade Abissal: entradas por seção, profundímetro, deriva do círculo e galeria tocável.
(function () {
    const calmo = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

    // 1. Entradas: cada bloco ganha .visivel quando entra na tela (uma vez só)
    const alvos = document.querySelectorAll("[data-entra], .declaracao, .cardapio, .marca-grande");
    if ("IntersectionObserver" in window && !calmo) {
        const observador = new IntersectionObserver((entradas) => {
            entradas.forEach((entrada) => {
                if (entrada.isIntersecting) {
                    entrada.target.classList.add("visivel");
                    observador.unobserve(entrada.target);
                }
            });
        }, { rootMargin: "0px 0px -12% 0px" });
        alvos.forEach((alvo) => observador.observe(alvo));
    } else {
        alvos.forEach((alvo) => alvo.classList.add("visivel"));
    }

    // 2. Depois que a luz acende, a gravura passa a flutuar devagar
    const gravura = document.querySelector(".hero-luz img");
    if (gravura && !calmo) {
        setTimeout(() => gravura.classList.add("flutua"), 1800);
    }

    // 3. Profundímetro: a rolagem é a descida até o campus, a 4.000 m
    const medidor = document.querySelector(".profundimetro");
    const luz = document.querySelector(".hero-luz");
    const zonas = [
        [200, "zona da luz do sol"],
        [1000, "zona do crepúsculo"],
        [3999, "zona da meia-noite"],
        [Infinity, "campus abissal"],
    ];
    const formato = new Intl.NumberFormat("pt-BR");

    function aoRolar() {
        const maximo = document.documentElement.scrollHeight - window.innerHeight;
        const fracao = maximo > 0 ? Math.min(1, window.scrollY / maximo) : 0;
        if (medidor) {
            const metros = Math.round(fracao * 4000);
            medidor.querySelector("strong").textContent = formato.format(metros) + " m";
            medidor.querySelector("span").textContent = zonas.find(([limite]) => metros <= limite)[1];
        }
        if (luz && !calmo) {
            luz.style.translate = `0 ${window.scrollY * 0.18}px`;
        }
    }
    window.addEventListener("scroll", aoRolar, { passive: true });
    aoRolar();

    // 4. Galeria: tocar na moldura passa para o próximo aluno
    const moldura = document.querySelector(".moldura");
    if (moldura) {
        const figuras = moldura.querySelectorAll("figure");
        let atual = 0;
        moldura.addEventListener("click", () => {
            moldura.classList.add("manual");
            figuras[atual].classList.remove("ativa");
            atual = (atual + 1) % figuras.length;
            figuras[atual].classList.add("ativa");
        });
        moldura.addEventListener("keydown", (e) => {
            if (e.key === "Enter" || e.key === " ") { e.preventDefault(); moldura.click(); }
        });
    }
})();
