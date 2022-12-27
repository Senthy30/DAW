function changePeriod() {
    startDate = document.getElementById("startDate").value;
    endDate = document.getElementById("endDate").value;
    window.location.href = "index.php?startDate=" + startDate + "&endDate=" + endDate;
}