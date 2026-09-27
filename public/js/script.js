function deleteActivity(id) {
    if (confirm('คุณแน่ใจหรือไม่ที่จะลบกิจกรรมนี้?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/activities/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById(`row-${id}`);
                if (row) {
                    row.remove();
                }
                alert(data.message);
            } else {
                alert('เกิดข้อผิดพลาดในการลบข้อมูล');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
        });
    }
}