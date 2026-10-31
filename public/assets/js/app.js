/**
 * AMVAC 2027 — Lightweight Accessible Frontend Script
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Accessible Mobile Menu Drawer
  const menuToggle = document.querySelector('.mobile-nav-toggle');
  const mobileDrawer = document.querySelector('.mobile-drawer');

  if (menuToggle && mobileDrawer) {
    menuToggle.addEventListener('click', () => {
      const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
      menuToggle.setAttribute('aria-expanded', !isExpanded);
      mobileDrawer.classList.toggle('is-open');
    });

    // Close mobile menu on Escape key
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && mobileDrawer.classList.contains('is-open')) {
        menuToggle.setAttribute('aria-expanded', 'false');
        mobileDrawer.classList.remove('is-open');
        menuToggle.focus();
      }
    });
  }

  // 2. Copy Reference Code Helper
  const copyBtn = document.querySelector('.btn-copy-ref');
  if (copyBtn) {
    copyBtn.addEventListener('click', () => {
      const refText = copyBtn.getAttribute('data-ref') || '';
      if (navigator.clipboard) {
        navigator.clipboard.writeText(refText).then(() => {
          const original = copyBtn.innerText;
          copyBtn.innerText = 'Copied to Clipboard!';
          copyBtn.classList.add('btn-gold');
          setTimeout(() => {
            copyBtn.innerText = original;
            copyBtn.classList.remove('btn-gold');
          }, 2500);
        });
      }
    });
  }

  // 3. Dynamic Fee Card Selection
  const feeCards = document.querySelectorAll('.fee-selector-card');
  const feeRadioInputs = document.querySelectorAll('input[name="fee_category"]');

  if (feeCards.length > 0) {
    feeCards.forEach(card => {
      card.addEventListener('click', () => {
        const radio = card.querySelector('input[type="radio"]');
        if (radio) {
          radio.checked = true;
          feeCards.forEach(c => c.classList.remove('selected'));
          card.classList.add('selected');
        }
      });
    });

    feeRadioInputs.forEach(radio => {
      radio.addEventListener('change', () => {
        feeCards.forEach(c => c.classList.remove('selected'));
        const parentCard = radio.closest('.fee-selector-card');
        if (parentCard) {
          parentCard.classList.add('selected');
        }
      });
    });
  }

  // 4. Initialize Continental Leadership Carousel
  initLeadershipCarousel();
});

  // --------------------------------------------------------------------------
  // Leadership Horizontal Carousel Controller
  // --------------------------------------------------------------------------
  function initLeadershipCarousel() {
    var track = document.getElementById('leadershipTrack');
    var prevBtn = document.getElementById('leaderPrevBtn');
    var nextBtn = document.getElementById('leaderNextBtn');
    var counterCurrent = document.getElementById('leaderCounterCurrent');
    var counterTotal = document.getElementById('leaderCounterTotal');
    var progressFill = document.getElementById('leaderProgressFill');
    var dotsContainer = document.getElementById('leaderCarouselDots');

    if (!track) return;

    var cards = track.querySelectorAll('.leader-carousel-card');
    var totalCards = cards.length;
    if (totalCards === 0) return;

    if (counterTotal) {
      counterTotal.textContent = String(totalCards).padStart(2, '0');
    }

    // Build dots
    if (dotsContainer) {
      dotsContainer.innerHTML = '';
      cards.forEach(function (_, idx) {
        var dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'carousel-dot' + (idx === 0 ? ' is-active' : '');
        dot.setAttribute('role', 'tab');
        dot.setAttribute('aria-label', 'Go to leadership profile ' + (idx + 1));
        dot.setAttribute('aria-selected', idx === 0 ? 'true' : 'false');
        dot.addEventListener('click', function () {
          scrollToIndex(idx);
        });
        dotsContainer.appendChild(dot);
      });
    }

    function getCardWidth() {
      if (cards.length > 0) {
        var style = window.getComputedStyle(track);
        var gap = parseFloat(style.gap) || 24;
        return cards[0].offsetWidth + gap;
      }
      return track.offsetWidth;
    }

    function scrollToIndex(idx) {
      var step = getCardWidth();
      track.scrollTo({
        left: idx * step,
        behavior: 'smooth'
      });
    }

    function updateState() {
      var scrollLeft = track.scrollLeft;
      var maxScroll = track.scrollWidth - track.clientWidth;
      var step = getCardWidth();
      var activeIndex = Math.min(totalCards - 1, Math.max(0, Math.round(scrollLeft / step)));

      // Counter
      if (counterCurrent) {
        counterCurrent.textContent = String(activeIndex + 1).padStart(2, '0');
      }

      // Progress bar fill
      if (progressFill) {
        var progressPercent = maxScroll > 0 ? ((scrollLeft / maxScroll) * 100) : 100;
        var minFill = (1 / totalCards) * 100;
        progressFill.style.width = Math.max(minFill, Math.min(100, progressPercent)) + '%';
      }

      // Dots
      if (dotsContainer) {
        var dots = dotsContainer.querySelectorAll('.carousel-dot');
        dots.forEach(function (dot, i) {
          if (i === activeIndex) {
            dot.classList.add('is-active');
            dot.setAttribute('aria-selected', 'true');
          } else {
            dot.classList.remove('is-active');
            dot.setAttribute('aria-selected', 'false');
          }
        });
      }

      // Buttons boundary disabling
      var isAtStart = scrollLeft <= 4;
      var isAtEnd = scrollLeft >= (maxScroll - 4);

      if (prevBtn) {
        prevBtn.disabled = isAtStart;
        prevBtn.setAttribute('aria-disabled', isAtStart ? 'true' : 'false');
      }
      if (nextBtn) {
        nextBtn.disabled = isAtEnd;
        nextBtn.setAttribute('aria-disabled', isAtEnd ? 'true' : 'false');
      }
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', function () {
        var step = getCardWidth();
        track.scrollBy({ left: -step, behavior: 'smooth' });
      });
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', function () {
        var step = getCardWidth();
        track.scrollBy({ left: step, behavior: 'smooth' });
      });
    }

    // Keyboard support on track
    track.addEventListener('keydown', function (e) {
      var step = getCardWidth();
      if (e.key === 'ArrowRight') {
        e.preventDefault();
        track.scrollBy({ left: step, behavior: 'smooth' });
      } else if (e.key === 'ArrowLeft') {
        e.preventDefault();
        track.scrollBy({ left: -step, behavior: 'smooth' });
      }
    });

    var scrollDebounce;
    track.addEventListener('scroll', function () {
      clearTimeout(scrollDebounce);
      scrollDebounce = setTimeout(updateState, 40);
    }, { passive: true });

    window.addEventListener('resize', updateState, { passive: true });
    updateState();
  }

