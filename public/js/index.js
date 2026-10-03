const slider = document.getElementById('diskonSlider');
const prev = document.getElementById('diskonPrev');
const next = document.getElementById('diskonNext');

if (slider && prev && next) {
  const scrollAmount = 220;

  next.onclick = () =>
    slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });

  prev.onclick = () =>
    slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
}

document.addEventListener("DOMContentLoaded", () => {
  const items = document.querySelectorAll("#diskonItems > a");

  if (items.length < 6) {
    prev.classList.add("hidden");
    next.classList.add("hidden");
  }
});

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

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("filterForm");
  const container = document.getElementById("produkContainer");

  if (!form || !container) return;

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    const params = new URLSearchParams(new FormData(form));

    fetch("?" + params.toString(), {
      headers: {
        "X-Requested-With": "XMLHttpRequest"
      }
    })
      .then(res => res.text())
      .then(html => {
        container.innerHTML = html;
        window.history.replaceState({}, "", "?" + params.toString());
      })
      .catch(err => {
        console.error("Filter error:", err);
      });
  });
});

document.addEventListener("click", function (e) {
    const link = e.target.closest("a");

    if (!link) return;

    if (!link.href.includes("page=")) return;

    e.preventDefault();

    fetch(link.href, {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(res => res.text())
    .then(html => {
        document.getElementById("produkContainer").innerHTML = html;
    })
    .catch(err => console.error(err));
});
