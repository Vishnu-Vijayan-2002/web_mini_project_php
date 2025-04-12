document.getElementById('fakePaymentForm').addEventListener('submit', function (e) {
    e.preventDefault();
  
    const amount = document.getElementById('amount').value;
    const button = document.getElementById('payBtn');
  
    if (!amount || parseFloat(amount) <= 0) {
      alert("Please enter a valid amount.");
      return;
    }
  
    button.disabled = true;
    button.textContent = `Processing ₹${amount}...`;
  
    setTimeout(() => {
      const isSuccess = Math.random() > 0.3;
      if (isSuccess) {
        alert(`✅ Payment of ₹${amount} successful! Thank you for your donation.`);
      } else {
        alert("❌ Payment Failed! Please try again.");
      }
  
      button.disabled = false;
      button.textContent = `Pay ₹${amount}`;
    }, 1500);
  });
  