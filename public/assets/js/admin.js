/**
 * AMVAC 2027 — Private Admin Area Script
 */

document.addEventListener('DOMContentLoaded', () => {
  // Modal handling for payment review
  const modalBackdrop = document.getElementById('reviewModal');
  const reviewForm = document.getElementById('reviewForm');
  const modalRefDisplay = document.getElementById('modalRefDisplay');
  const modalReviewId = document.getElementById('modalReviewId');
  const modalDecision = document.getElementById('modalDecision');
  const modalDecisionLabel = document.getElementById('modalDecisionLabel');

  window.openReviewModal = function(reviewId, ref, decision) {
    if (!modalBackdrop) return;
    modalReviewId.value = reviewId;
    modalDecision.value = decision;
    modalRefDisplay.innerText = ref;
    modalDecisionLabel.innerText = decision === 'approve' ? 'Approve & Confirm Paid' : 'Reject Payment Reference';
    
    const submitBtn = document.getElementById('modalSubmitBtn');
    if (decision === 'approve') {
      submitBtn.className = 'btn btn-gold';
      submitBtn.innerText = 'Confirm Bank Credit';
    } else {
      submitBtn.className = 'btn btn-navy';
      submitBtn.style.backgroundColor = 'var(--state-danger)';
      submitBtn.innerText = 'Confirm Rejection';
    }

    modalBackdrop.classList.add('is-open');
  };

  window.closeReviewModal = function() {
    if (modalBackdrop) {
      modalBackdrop.classList.remove('is-open');
    }
  };

  if (modalBackdrop) {
    modalBackdrop.addEventListener('click', (e) => {
      if (e.target === modalBackdrop) {
        closeReviewModal();
      }
    });
  }
});
