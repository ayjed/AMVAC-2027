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
});
