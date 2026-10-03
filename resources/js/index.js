// LOADING SCREEN
window.addEventListener("load", () => {
  const loader = document.getElementById("pageLoader");
  if (loader) {
    loader.classList.add("opacity-0", "transition-opacity", "duration-500");
    setTimeout(() => loader.style.display = "none", 500);
  }
});

// NAVBAR SCROLL EFFECT
document.addEventListener("DOMContentLoaded", () => {
  const navbar = document.getElementById("navbar");
  let lastScroll = 0;

  window.addEventListener("scroll", () => {
    const currentScroll = window.pageYOffset;

    if (currentScroll === 0) {
      navbar.style.transform = "translateY(0)";
      navbar.classList.remove("shadow-md", "bg-white/40", "backdrop-blur-sm", "rounded-2xl");
      lastScroll = currentScroll;
      return;
    }

    if (currentScroll > lastScroll) {
      navbar.style.transform = "translateY(10px)";
      navbar.classList.add("shadow-md", "bg-white/40", "backdrop-blur-sm", "rounded-2xl");
    }

    lastScroll = currentScroll;
  });
});

// PRODUK DISKON
function generateDiskonProducts() {
  const container = document.getElementById("produkDiskon");
  if (!container) return;

  for (let i = 0; i < 6; i++) {
    const card = document.createElement("a");
    card.href = "./model/detail_product.html";
    card.className = "block";

    card.innerHTML = `
      <div class="bg-white rounded-xl drop-shadow-md overflow-hidden border border-[var(--primary)]/20 hover:shadow-lg transition">
        <img src="./assets/images/paracetamol.jpg" class="w-full aspect-square object-cover">
        <div class="w-full h-px bg-gray-200"></div>
        <div class="p-3 text-[13px]">
          <p class="font-medium">Sanmol Paracetamol 500mg</p>
          <p class="text-[12px] text-gray-500 mt-1">Obat Bebas</p>
          <p class="text-[var(--primary)] mt-1 font-semibold">
            Rp. 14.000
            <span class="line-through text-[12px] text-gray-500">Rp. 15.000</span>
          </p>
        </div>
      </div>
    `;
    container.appendChild(card);
  }
}

// PRODUK SEMUA
function generateProducts() {
  const container = document.getElementById("produkSemua");
  if (!container) return;

  for (let i = 0; i < 18; i++) {
    const card = document.createElement("a");
    card.href = "./model/detail_product.html";
    card.className = "block";

    card.innerHTML = `
      <div class="bg-white rounded-xl drop-shadow-md overflow-hidden border border-[var(--primary)]/20 hover:shadow-lg transition">
        <img src="./assets/images/paracetamol.jpg" class="w-full aspect-square object-cover">
        <div class="w-full h-px bg-gray-200"></div>
        <div class="p-3 text-[13px]">
          <p class="font-medium">Sanmol Paracetamol 500mg</p>
          <p class="text-[12px] text-gray-500 mt-1">Obat Bebas</p>
          <p class="text-[var(--primary)] mt-1 font-semibold">Rp. 14.000</p>
        </div>
      </div>
    `;
    container.appendChild(card);
  }
}

// HERO SLIDER
document.addEventListener("DOMContentLoaded", () => {
  const hero = document.getElementById("hero");
  let currentImage = document.getElementById("heroImg");

  const banners = [
    "./assets/images/banner_1.png",
    "./assets/images/banner_2.png"
  ];

  let index = 0;

  function slide(dir) {
    let nextIndex = dir === "right" ? index + 1 : index - 1;
    if (nextIndex >= banners.length) nextIndex = 0;
    if (nextIndex < 0) nextIndex = banners.length - 1;

    const newImage = currentImage.cloneNode();
    newImage.src = banners[nextIndex];

    newImage.style.transform = dir === "right"
      ? "translateX(100%)"
      : "translateX(-100%)";

    hero.appendChild(newImage);

    requestAnimationFrame(() => {
      currentImage.style.transform =
        dir === "right" ? "translateX(-100%)" : "translateX(100%)";

      newImage.style.transform = "translateX(0)";
    });

    setTimeout(() => {
      currentImage.remove();
      currentImage = newImage;
      index = nextIndex;
    }, 500);
  }

  document.getElementById("nextBtn").onclick = () => slide("right");
  document.getElementById("prevBtn").onclick = () => slide("left");
});

// FILTER
document.addEventListener("DOMContentLoaded", () => {
  const filterButton = document.getElementById("filterButton");
  const filterMenu = document.getElementById("filterMenu");

  filterButton.onclick = () => {
    filterMenu.classList.toggle("hidden");
  };

  document.addEventListener("click", (e) => {
    if (!filterButton.contains(e.target) && !filterMenu.contains(e.target)) {
      filterMenu.classList.add("hidden");
    }
  });

  document.getElementById("applyFilterBtn").onclick = () => {
    const kategori = [...document.querySelectorAll("input[name='kategori']:checked")].map(v => v.value);
    const brand = [...document.querySelectorAll("input[name='brand']:checked")].map(v => v.value);

    console.log("Kategori:", kategori);
    console.log("Brand:", brand);

    filterMenu.classList.add("hidden");
  };

  document.getElementById("resetFilterBtn").onclick = () => {
    document.querySelectorAll("input[name='kategori'], input[name='brand']").forEach(el => el.checked = false);
  };
});
