const teacherPass = document.getElementById('teacherPass');
const toggleTeacherPass = document.getElementById('toggleTeacherPass');

if (toggleTeacherPass && teacherPass) {
   toggleTeacherPass.addEventListener('click', () => {
      if (teacherPass.type === 'password') {
         teacherPass.type = 'text';
         toggleTeacherPass.classList.remove('fa-eye');
         toggleTeacherPass.classList.add('fa-eye-slash');
      } else {
         teacherPass.type = 'password';
         toggleTeacherPass.classList.remove('fa-eye-slash');
         toggleTeacherPass.classList.add('fa-eye');
      }
   });
}