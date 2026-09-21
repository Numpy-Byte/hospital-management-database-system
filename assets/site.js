document.addEventListener('DOMContentLoaded', () => {
  const targets = document.querySelectorAll('.page-heading, .stats, .panel, .team-grid, .empty-state, .auth-card, .home-hero, .home-section, .home-band');
  if (!('IntersectionObserver' in window)) {
    targets.forEach((element) => element.classList.add('is-visible'));
    return;
  }
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add('is-visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.08 });
  targets.forEach((element) => observer.observe(element));
});
