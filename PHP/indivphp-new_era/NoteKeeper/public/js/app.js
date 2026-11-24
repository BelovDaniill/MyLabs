document.addEventListener('DOMContentLoaded', () => {
  // Bootstrap client-side validation.
  document.querySelectorAll('.needs-validation').forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });

  // Show only the additional fields matching the selected note type.
  const typeSelect = document.getElementById('note-type');
  if (typeSelect) {
    const updateFields = () => {
      document.querySelectorAll('.type-fields').forEach(block => {
        const active = block.dataset.type === typeSelect.value;
        block.classList.toggle('active', active);
        block.querySelectorAll('input, select, textarea').forEach(input => {
          input.disabled = !active;
        });
      });
    };
    typeSelect.addEventListener('change', updateFields);
    updateFields();
  }
});
