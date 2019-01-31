const contacts = document.getElementById('contacts');
if (contacts) {
  contacts.addEventListener('click', e => {
    if (e.target.className === 'btn btn-danger delete-article btn-sm') {
      if (confirm('Are you sure?')) {
        const id = e.target.getAttribute('data-id');

        fetch(`/contact/delete/${id}`, {
          method: 'DELETE'
        }).then(res => {});
      }
    }
  });
}

const addresses = document.getElementById('addresses');
if (addresses) {
  addresses.addEventListener('click', e => {
    if (e.target.className === 'btn btn-danger delete-article btn-sm') {
      if (confirm('Are you sure?')) {
        const id = e.target.getAttribute('data-id');

        fetch(`/address/delete/${id}`, {
          method: 'DELETE'
        }).then(res => {});
      }
    }
  });
}
