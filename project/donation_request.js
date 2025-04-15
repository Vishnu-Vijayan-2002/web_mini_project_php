function validateForm() {
  // Validate required fields
  const fullName = document.forms["donationForm"]["full_name"].value;
  const age = document.forms["donationForm"]["age"].value;
  const disease = document.forms["donationForm"]["disease"].value;
  const contact = document.forms["donationForm"]["contact"].value;
  const upi = document.forms["donationForm"]["upi"].value;
  const accountNumber = document.forms["donationForm"]["account_number"].value;
  const ifsc = document.forms["donationForm"]["ifsc"].value;
  const branch = document.forms["donationForm"]["branch"].value;
  const amount = document.forms["donationForm"]["amount"].value;
  const certificate = document.forms["donationForm"]["certificate"].value;

  if (!fullName || !age || !disease || !contact || !upi || !accountNumber || !ifsc || !branch || !amount || !certificate) {
    alert("All fields must be filled out.");
    return false;
  }

  // Validate file type for the certificate
  const allowedExtensions = /(\.pdf|\.jpg|\.jpeg|\.png)$/i;
  if (!allowedExtensions.exec(certificate)) {
    alert("Invalid file type. Only PDF, JPG, JPEG, and PNG files are allowed.");
    return false;
  }

  // Validate UPI ID format (simple check for non-empty)
  const upiPattern = /^[a-zA-Z0-9]{1,}$/;
  if (!upi.match(upiPattern)) {
    alert("Invalid UPI ID format.");
    return false;
  }

  // Validate Account Number (simple numeric check)
  const accountPattern = /^[0-9]+$/;
  if (!accountNumber.match(accountPattern)) {
    alert("Account Number must be numeric.");
    return false;
  }

  // Validate Amount (should be a number)
  if (isNaN(amount) || amount <= 0) {
    alert("Amount must be a valid number greater than 0.");
    return false;
  }

  return true;
}
