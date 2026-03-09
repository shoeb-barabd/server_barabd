const container = document.getElementById("container");
const registerBtn = document.getElementById("register");
const loginBtn = document.getElementById("login");

if (container && container.dataset.defaultMode === "register") {
  container.classList.add("active");
}

// common timeline settings
function playSwitchAnimation(onMiddle) {
  const tl = gsap.timeline();

  tl.to(container, {
    duration: 0.25,
    scale: 0.97,
    opacity: 0.8,
    ease: "power1.out"
  })
    .add(onMiddle) // run class add/remove in the middle of the animation
    .to(container, {
      duration: 0.35,
      scale: 1,
      opacity: 1,
      ease: "power2.out"
    });

  return tl;
}

registerBtn?.addEventListener("click", () => {
  playSwitchAnimation(() => {
    container?.classList.add("active");
  });
});

loginBtn?.addEventListener("click", () => {
  playSwitchAnimation(() => {
    container?.classList.remove("active");
  });
});

// optional: nice entrance animation on page load
if (container) {
  gsap.from(container, {
    duration: 0.6,
    y: 40,
    opacity: 0,
    scale: 0.95,
    ease: "power2.out"
  });
}
