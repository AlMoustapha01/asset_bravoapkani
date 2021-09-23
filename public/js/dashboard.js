document.addEventListener("DOMContentLoaded", function(event) {

    

    const showNavbar = (toggleId, navId, bodyId, headerId) =>{
    const toggle = document.getElementById(toggleId),
    nav = document.getElementById(navId),
    bodypd = document.getElementById(bodyId),
    headerpd = document.getElementById(headerId)
    
    // Validate that all variables exist
    if(toggle && nav && bodypd && headerpd){
        toggle.addEventListener('click', ()=>{
        // show navbar
        nav.classList.toggle('show')
        // change icon
        toggle.classList.toggle('bx-x')
        // add padding to body
        bodypd.classList.toggle('body-pd')
        // add padding to header
        headerpd.classList.toggle('body-pd')
        })
        }
    }
    
    showNavbar('header-toggle','nav-bar','body-pd','header')
    
    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link')
    
    function colorLink(){
        if(linkColor){
            linkColor.forEach(l=> l.classList.remove('active'))
            this.classList.add('active')
        }
    }
    linkColor.forEach(l=> l.addEventListener('click', colorLink))
    
    // Your code to run since DOM is loaded and ready
    });
    var showContent = (content) =>{
        var dashboard = document.getElementById("dashboard");
        var acc = document.getElementById("acc");
        var vol = document.getElementById("vol");
        var roles = document.getElementById("roles");
        if(content=='dashboard' && dashboard.style.display=="none"){
            dashboard.style.display= "block";
            acc.style.display= "none";
            vol.style.display= "none";
            roles.style.display= "none";
        }
        if(content=='vol' && vol.style.display=="none"){
            vol.style.display= "block";
            acc.style.display= "none";
            dashboard.style.display= "none";
            roles.style.display= "none";
        }
        if(content=='acc' && acc.style.display=="none"){
            acc.style.display= "block";
            dashboard.style.display= "none";
            vol.style.display= "none";
            roles.style.display= "none";
        }
        if(content=='roles' && roles.style.display=="none"){
            roles.style.display= "block";
            dashboard.style.display= "none";
            vol.style.display= "none";
            acc.style.display= "none";
        }
        
    }