<script>
function loadDonations() {
    fetch('fetch_donations.php')
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById("donation-table-body");
            tbody.innerHTML = "";

            data.forEach(donation => {
                const row = document.createElement("tr");
                row.setAttribute("data-id", donation.donation_id);

                row.innerHTML = `
                    <td>#${donation.donation_id}</td>
                    <td>${donation.food_type}</td>
                    <td>${donation.quantity}</td>
                    <td>${donation.location}</td>
                    <td>${donation.donor}</td>
                    <td><span class="status status-${donation.donor_status.toLowerCase()}-donor">${donation.donor_status}</span></td>
                    <td><span class="status status-${donation.status.toLowerCase()}">${donation.status}</span></td>
                    <td>
                        <button class="action-btn pickup-btn" onclick="openModal('${donation.donation_id}')" ${donation.donor_status === 'Busy' ? 'disabled' : ''}>Pick Up</button>
                        <button class="action-btn view-btn" onclick="viewDetails('${donation.donation_id}')">View</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        });
}

// Call it on page load
window.onload = loadDonations;
</script>
