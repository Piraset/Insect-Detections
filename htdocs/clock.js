function updateClock() {
    const now = new Date();
    const date = now.toLocaleDateString();
    const time = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'});
    const clock = document.getElementById('clock');
    clock.textContent = `${date} ${time}`;
  }
  
  setInterval(updateClock, 1000);
  