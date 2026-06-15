const uploadInputs = document.querySelectorAll('input[type="file"][name="payment_receipt"]');

uploadInputs.forEach((input) => {
   const uploadArea = input.closest('.upload-area');
   const form = input.closest('form');
   const fileName = form ? form.querySelector('.file-name') : null;

   input.addEventListener('change', () => {
      if (input.files.length > 0) {
         fileName.textContent = input.files[0].name;
         uploadArea.classList.add('active');
      } else {
         fileName.textContent = 'No file selected';
         uploadArea.classList.remove('active');
      }
   });
});