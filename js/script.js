let profile = document.querySelector('.header .flex .profile');
let searchForm = document.querySelector('.header .flex .search-form');
let sideBar = document.querySelector('.side-bar');
let toggleBtn = document.getElementById('toggle-btn');
let body = document.body;

/* profile button */
if(document.querySelector('#user-btn')){
   document.querySelector('#user-btn').onclick = () =>{
      if(profile){
         profile.classList.toggle('active');
      }
      if(searchForm){
         searchForm.classList.remove('active');
      }
   };
}

/* search button */
if(document.querySelector('#search-btn')){
   document.querySelector('#search-btn').onclick = () =>{
      if(searchForm){
         searchForm.classList.toggle('active');
      }
      if(profile){
         profile.classList.remove('active');
      }
   };
}

/* menu button */
if(document.querySelector('#menu-btn')){
   document.querySelector('#menu-btn').onclick = () =>{
      if(sideBar){
         sideBar.classList.toggle('active');
      }
      body.classList.toggle('active');
   };
}

/* close sidebar button */
if(document.querySelector('#close-btn')){
   document.querySelector('#close-btn').onclick = () =>{
      if(sideBar){
         sideBar.classList.remove('active');
      }
      body.classList.remove('active');
   };
}

/* dark mode */
let darkMode = localStorage.getItem('dark-mode');

const enableDarkMode = () =>{
   if(toggleBtn){
      toggleBtn.classList.replace('fa-sun', 'fa-moon');
   }
   body.classList.add('dark');
   localStorage.setItem('dark-mode', 'enabled');
};

const disableDarkMode = () =>{
   if(toggleBtn){
      toggleBtn.classList.replace('fa-moon', 'fa-sun');
   }
   body.classList.remove('dark');
   localStorage.setItem('dark-mode', 'disabled');
};

if(darkMode === 'enabled'){
   enableDarkMode();
}

if(toggleBtn){
   toggleBtn.onclick = () =>{
      darkMode = localStorage.getItem('dark-mode');

      if(darkMode === 'disabled' || darkMode === null){
         enableDarkMode();
      }else{
         disableDarkMode();
      }
   };
}

/* scroll close */
window.onscroll = () =>{
   if(profile){
      profile.classList.remove('active');
   }

   if(searchForm){
      searchForm.classList.remove('active');
   }

   if(window.innerWidth < 1200){
      if(sideBar){
         sideBar.classList.remove('active');
      }
      body.classList.remove('active');
   }
};