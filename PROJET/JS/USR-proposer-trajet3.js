document.addEventListener("DOMContentLoaded", () => {

    // === Confettis simples ===
    function createConfetti() {
        const confetti = document.createElement('div');
        confetti.classList.add('confetti');
        confetti.style.position = 'fixed';
        confetti.style.width = '10px';
        confetti.style.height = '10px';
        confetti.style.backgroundColor = `hsl(${Math.random()*360}, 100%, 50%)`;
        confetti.style.top = '-10px';
        confetti.style.left = `${Math.random()*window.innerWidth}px`;
        confetti.style.zIndex = 9999;
        confetti.style.opacity = Math.random();
        confetti.style.borderRadius = '50%';
        document.body.appendChild(confetti);

        let falling = setInterval(() => {
            confetti.style.top = parseFloat(confetti.style.top) + Math.random()*5 + 'px';
            confetti.style.left = parseFloat(confetti.style.left) + (Math.random()*2 -1) + 'px';
            if(parseFloat(confetti.style.top) > window.innerHeight){
                clearInterval(falling);
                confetti.remove();
            }
        }, 20);
    }

    // Générer 100 confettis en quelques secondes
    for(let i=0; i<100; i++){
        setTimeout(createConfetti, i*30);
    }

});
