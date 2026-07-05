document.addEventListener("DOMContentLoaded", () => {

    const codeInputs = document.querySelectorAll(".code-input");
    const hiddenInput = document.querySelector(".verification-hidden");

    // Si la página no tiene el componente, no hace nada
    if (codeInputs.length === 0 || !hiddenInput) {
        return;
    }

    function actualizarCodigo() {
        hiddenInput.value = "";

        codeInputs.forEach(input => {
            hiddenInput.value += input.value;
        });
    }

    codeInputs.forEach((input, index) => {

        // Solo números
        input.addEventListener("input", function () {

            this.value = this.value.replace(/\D/g, "");

            actualizarCodigo();

            // Pasar al siguiente cuadro automáticamente
            if (this.value !== "" && index < codeInputs.length - 1) {
                codeInputs[index + 1].focus();
            }

        });

        // Retroceder con Backspace
        input.addEventListener("keydown", function (e) {

            if (e.key === "Backspace" && this.value === "" && index > 0) {
                codeInputs[index - 1].focus();
            }

        });

        // Permitir pegar el código completo (ej. 1739)
        input.addEventListener("paste", function (e) {

            e.preventDefault();

            const texto = (e.clipboardData || window.clipboardData)
                .getData("text")
                .replace(/\D/g, "")
                .substring(0, codeInputs.length);

            texto.split("").forEach((numero, i) => {
                if (codeInputs[i]) {
                    codeInputs[i].value = numero;
                }
            });

            actualizarCodigo();

            const ultimo = Math.min(texto.length, codeInputs.length) - 1;
            if (ultimo >= 0) {
                codeInputs[ultimo].focus();
            }

        });

    });
});