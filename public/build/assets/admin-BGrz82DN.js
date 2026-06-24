class o{constructor(){this.sidebar=document.getElementById("adminSidebar"),this.toggleIcon=document.getElementById("adminToggleIcon"),this.sidebarOverlay=document.getElementById("adminSidebarOverlay"),this.init()}init(){this.initSidebar(),this.initMobileEvents(),this.initAOS(),this.initCharts(),this.initNotifications(),this.initTabs(),this.restoreSidebarState()}initAOS(){typeof AOS<"u"&&AOS.init({duration:600,easing:"ease-out-cubic",once:!0})}initSidebar(){const i=document.querySelector(".admin-sidebar-toggle");i&&i.addEventListener("click",()=>this.toggleSidebar())}toggleSidebar(){if(!this.sidebar)return;const i=document.body,t=document.querySelector(".admin-main-content");this.sidebar.classList.toggle("collapsed"),this.sidebar.classList.contains("collapsed")?(t&&t.classList.add("sidebar-collapsed"),i.classList.add("sidebar-collapsed"),this.toggleIcon&&(this.toggleIcon.classList.remove("fa-chevron-left"),this.toggleIcon.classList.add("fa-chevron-right"))):(t&&t.classList.remove("sidebar-collapsed"),i.classList.remove("sidebar-collapsed"),this.toggleIcon&&(this.toggleIcon.classList.remove("fa-chevron-right"),this.toggleIcon.classList.add("fa-chevron-left"))),localStorage.setItem("adminSidebarCollapsed",this.sidebar.classList.contains("collapsed"))}restoreSidebarState(){if(localStorage.getItem("adminSidebarCollapsed")==="true"&&this.sidebar&&this.toggleIcon){const t=document.body;this.sidebar.classList.add("collapsed"),t.classList.add("sidebar-collapsed"),this.toggleIcon.classList.remove("fa-chevron-left"),this.toggleIcon.classList.add("fa-chevron-right")}}openSidebar(){this.sidebar&&this.sidebarOverlay&&(this.sidebar.classList.add("show"),this.sidebarOverlay.classList.add("show"))}closeSidebar(){this.sidebar&&this.sidebarOverlay&&(this.sidebar.classList.remove("show"),this.sidebarOverlay.classList.remove("show"))}initMobileEvents(){const i=document.querySelector(".admin-mobile-toggle");i&&i.addEventListener("click",()=>this.openSidebar()),this.sidebarOverlay&&this.sidebarOverlay.addEventListener("click",()=>this.closeSidebar()),document.querySelectorAll(".admin-nav-link").forEach(t=>{t.addEventListener("click",()=>{window.innerWidth<=768&&this.closeSidebar()})}),window.addEventListener("resize",()=>{window.innerWidth>768&&this.closeSidebar()})}initCharts(){this.initUsersChart(),this.initCoursesChart(),this.initRevenueChart()}initUsersChart(){const i=document.getElementById("usersChart");i&&new Chart(i,{type:"line",data:{labels:["Jan","Fév","Mar","Avr","Mai","Jun"],datasets:[{label:"Nouveaux utilisateurs",data:[12,19,3,5,2,3],borderColor:"#11c06d",backgroundColor:"rgba(30, 163, 139, 0.1)",tension:.4}]},options:{responsive:!0,maintainAspectRatio:!1,plugins:{legend:{display:!1}},scales:{y:{beginAtZero:!0,grid:{color:"#E9ECEF"}},x:{grid:{display:!1}}}}})}initCoursesChart(){const i=document.getElementById("coursesChart");i&&new Chart(i,{type:"doughnut",data:{labels:["Publiés","Brouillons","Archivés"],datasets:[{data:[65,25,10],backgroundColor:["#11c06d","#fea837","#6C757D"],borderWidth:0}]},options:{responsive:!0,maintainAspectRatio:!1,plugins:{legend:{position:"bottom",labels:{padding:20,usePointStyle:!0}}}}})}initRevenueChart(){const i=document.getElementById("revenueChart");i&&new Chart(i,{type:"bar",data:{labels:["Jan","Fév","Mar","Avr","Mai","Jun"],datasets:[{label:"Revenus (€)",data:[1200,1900,800,1500,2e3,1800],backgroundColor:"#fea837",borderRadius:6}]},options:{responsive:!0,maintainAspectRatio:!1,plugins:{legend:{display:!1}},scales:{y:{beginAtZero:!0,grid:{color:"#E9ECEF"}},x:{grid:{display:!1}}}}})}initNotifications(){const i=document.querySelector(".admin-notifications");i&&i.addEventListener("click",()=>{this.showNotifications()})}showNotifications(){const i=document.querySelector(".admin-notifications-dropdown");if(i){i.remove();return}const t=document.createElement("div");t.className="admin-notifications-dropdown",t.innerHTML=`
            <div class="admin-notifications-header">
                <h6>Notifications</h6>
                <span class="admin-notifications-count">3</span>
            </div>
            <div class="admin-notifications-list">
                <div class="admin-notification-item">
                    <div class="admin-notification-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="admin-notification-content">
                        <p>Nouvel utilisateur inscrit</p>
                        <small>Il y a 5 minutes</small>
                    </div>
                </div>
                <div class="admin-notification-item">
                    <div class="admin-notification-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="admin-notification-content">
                        <p>Nouveau cours publié</p>
                        <small>Il y a 1 heure</small>
                    </div>
                </div>
                <div class="admin-notification-item">
                    <div class="admin-notification-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="admin-notification-content">
                        <p>Nouveau paiement reçu</p>
                        <small>Il y a 2 heures</small>
                    </div>
                </div>
            </div>
            <div class="admin-notifications-footer">
                <a href="#">Voir toutes les notifications</a>
            </div>
        `,document.querySelector(".admin-notifications").parentNode.appendChild(t),setTimeout(()=>{document.addEventListener("click",s=>{!s.target.closest(".admin-notifications")&&!s.target.closest(".admin-notifications-dropdown")&&t.remove()},{once:!0})},100)}initTabs(){const i=document.querySelectorAll(".admin-tab-btn"),t=document.querySelectorAll(".admin-tab-pane");i.forEach(e=>{e.addEventListener("click",()=>{const s=e.getAttribute("data-tab");i.forEach(a=>a.classList.remove("active")),t.forEach(a=>a.classList.remove("active")),e.classList.add("active");const n=document.getElementById(s);n&&n.classList.add("active")})})}showToast(i,t="success"){const e=document.createElement("div");e.className=`admin-toast admin-toast-${t}`,e.innerHTML=`
            <div class="admin-toast-content">
                <i class="fas fa-${t==="success"?"check":"exclamation"}-circle"></i>
                <span>${i}</span>
            </div>
            <button class="admin-toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `,document.body.appendChild(e),setTimeout(()=>{e.parentElement&&e.remove()},5e3)}confirmAction(i,t){const e=document.createElement("div");e.className="admin-confirm-modal",e.innerHTML=`
            <div class="admin-confirm-backdrop"></div>
            <div class="admin-confirm-dialog">
                <div class="admin-confirm-header">
                    <h6>Confirmation</h6>
                </div>
                <div class="admin-confirm-body">
                    <p>${i}</p>
                </div>
                <div class="admin-confirm-footer">
                    <button class="admin-btn admin-btn-secondary" onclick="this.closest('.admin-confirm-modal').remove()">
                        Annuler
                    </button>
                    <button class="admin-btn admin-btn-primary" onclick="window.adminConfirmCallback(); this.closest('.admin-confirm-modal').remove()">
                        Confirmer
                    </button>
                </div>
            </div>
        `,document.body.appendChild(e),window.adminConfirmCallback=t}}document.addEventListener("DOMContentLoaded",function(){window.adminInterface=new o});
