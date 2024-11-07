function toggleCareerSelect() {

    const roleSelect = document.getElementById('role');
    const careerContainer = document.getElementById('career-container');
    const estudianteRoleId = 1; 

    if (roleSelect.value == estudianteRoleId) {
        careerContainer.style.display = 'block';
    } else {
        careerContainer.style.display = 'none';
        document.getElementById('career').value = '';
    }
}