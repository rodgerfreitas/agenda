const contacts = document.getElementById('contacts');
if (contacts) {
  contacts.addEventListener('click', e => {
    if (e.target.className === 'btn btn-danger delete-article btn-sm') {
      if (confirm('Deseja excluir este contato?')) {
        const id = e.target.getAttribute('data-id');

        fetch(`/contact/delete/${id}`, {
          method: 'DELETE'
        }).then(res => window.location.reload());
      }
    }
  });
}

const addresses = document.getElementById('addresses');
if (addresses) {
  addresses.addEventListener('click', e => {
    if (e.target.className === 'btn btn-danger delete-article btn-sm') {
      if (confirm('Deseja excluir este endereço?')) {
        const id = e.target.getAttribute('data-id');

        fetch(`/address/delete/${id}`, {
          method: 'DELETE'
        }).then(res => window.location.reload());
      }
    }
  });
}





