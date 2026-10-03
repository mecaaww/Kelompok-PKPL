window.addEventListener("load", () => {
  const loader = document.getElementById("pageLoader");
  if (loader) {
    loader.classList.add("opacity-0", "transition-opacity", "duration-500");
    setTimeout(() => loader.style.display = "none", 500);
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const thumbs = document.querySelectorAll(".thumb");
  const mainImg = document.getElementById("mainImg");

  thumbs.forEach(t => {
    t.addEventListener("click", () => {
      mainImg.classList.add("opacity-0");
      mainImg.src = t.dataset.img;
    });
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("toggleDesc");
  const full = document.getElementById("fullDesc");
  let open = false;

  btn.onclick = () => {
    open = !open;
    full.classList.toggle("hidden");
    btn.textContent = open ? "Sembunyikan" : "Lihat Selengkapnya";
  };
});

document.addEventListener("DOMContentLoaded", () => {
  const btn = document.getElementById("toggleDesc");
  const content = document.getElementById("descContent");
  const fade = document.getElementById("descFade");

  let open = false;

  btn.addEventListener("click", () => {
    open = !open;

    if (open) {
      content.classList.remove("clamp-12");
      fade.style.display = "none";
      btn.textContent = "Sembunyikan";
    } else {
      content.classList.add("clamp-12");
      fade.style.display = "block";
      btn.textContent = "Lihat Selengkapnya";
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const thumbs = document.querySelectorAll(".thumb");
  const mainImg = document.getElementById("mainImg");

  thumbs.forEach(thumb => {
    thumb.addEventListener("click", () => {
      mainImg.classList.add("opacity-0");
      mainImg.src = thumb.dataset.img;
      mainImg.onload = () => {
        mainImg.classList.remove("opacity-0");
      };
    });
  });
});
