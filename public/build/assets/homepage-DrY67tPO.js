document.addEventListener("DOMContentLoaded",function(){(()=>{const n=document.createElement("div");n.className="scroll-indicator",n.innerHTML='<div class="scroll-progress"></div>',document.body.prepend(n);const t=()=>{const e=window.pageYOffset,o=document.documentElement.scrollHeight-window.innerHeight,s=e/o*100;document.querySelector(".scroll-progress").style.width=s+"%"};window.addEventListener("scroll",t)})();const d=()=>{const n=document.querySelectorAll(".stat-number"),t=(o,s)=>{let r=0;const a=parseInt(s),c=s.includes("%"),y=a/100,x=setInterval(()=>{r+=y,r>=a?(o.textContent=a+(c?"%":"+"),clearInterval(x)):o.textContent=Math.floor(r)+(c?"%":"+")},20)},e=new IntersectionObserver(o=>{o.forEach(s=>{if(s.isIntersecting){const r=s.target,a=r.textContent;t(r,a),e.unobserve(r)}})},{threshold:.5});n.forEach(o=>{e.observe(o)})},m=()=>{const n=document.querySelectorAll(".program-card, .feature-item, .testimonial-card");n.forEach((e,o)=>{e.style.opacity="0",e.style.transform="translateY(50px)",e.style.transition="opacity 0.6s ease, transform 0.6s ease"});const t=new IntersectionObserver(e=>{e.forEach((o,s)=>{o.isIntersecting&&(setTimeout(()=>{o.target.style.opacity="1",o.target.style.transform="translateY(0)"},s*100),t.unobserve(o.target))})},{threshold:.2});n.forEach(e=>t.observe(e))},f=()=>{const n=document.querySelector(".hero-main");if(!n)return;const t=()=>{const s=window.pageYOffset,r=s*.3;s<n.offsetHeight&&(n.style.transform=`translateY(${r}px)`)};let e=!1;const o=()=>{e||(requestAnimationFrame(()=>{t(),e=!1}),e=!0)};window.addEventListener("scroll",o)},u=()=>{document.querySelectorAll(".badge-item").forEach((t,e)=>{const o=e*500;t.style.animationDelay=`${o}ms`,t.addEventListener("mouseenter",()=>{t.style.transform="scale(1.1) translateY(-5px)",t.style.boxShadow="0 10px 20px rgba(0,0,0,0.2)"}),t.addEventListener("mouseleave",()=>{t.style.transform="scale(1) translateY(0)",t.style.boxShadow="0 4px 8px rgba(0,0,0,0.15)"})})},p=()=>{document.querySelectorAll('a[href^="#"]').forEach(n=>{n.addEventListener("click",function(t){t.preventDefault();const e=document.querySelector(this.getAttribute("href"));if(e){const o=e.offsetTop-80;window.scrollTo({top:o,behavior:"smooth"})}})})},h=()=>{const n=document.querySelector(".hero-title");if(!n)return;const t=n.textContent;n.textContent="";let e=0;const o=()=>{e<t.length&&(n.textContent+=t.charAt(e),e++,setTimeout(o,50))};setTimeout(o,500)},g=()=>{document.querySelectorAll("form").forEach(t=>{t.addEventListener("submit",function(e){const o=t.querySelectorAll("input[required]");let s=!0;if(o.forEach(r=>{r.value.trim()||(s=!1,r.classList.add("is-invalid"),r.addEventListener("input",()=>{r.classList.remove("is-invalid")}))}),!s){e.preventDefault();const r=t.querySelector(".is-invalid");r&&(r.focus(),r.scrollIntoView({behavior:"smooth",block:"center"}))}})})},v=()=>{const n={root:null,rootMargin:"0px 0px -100px 0px",threshold:.1},t=new IntersectionObserver(e=>{e.forEach(o=>{o.isIntersecting&&o.target.classList.add("animate-in")})},n);document.querySelectorAll(".animate-on-scroll").forEach(e=>{t.observe(e)})};(()=>{document.querySelector(".hero-main")&&(d(),m(),f(),u(),h()),p(),g(),v(),document.body.classList.add("loaded")})();let i;window.addEventListener("resize",()=>{clearTimeout(i),i=setTimeout(()=>{console.log("Window resized - recalculating positions")},250)}),window.location.hostname==="localhost"&&new PerformanceObserver(t=>{t.getEntries().forEach(e=>{console.log(`${e.name}: ${e.duration}ms`)})}).observe({entryTypes:["navigation","paint"]})});const b=`
    .page-loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #11c06d, #239e79);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        transition: opacity 0.3s ease;
    }
    
    .loader-content {
        text-align: center;
        color: white;
    }
    
    .logo-animation {
        width: 60px;
        height: 60px;
        border: 3px solid rgba(255,255,255,0.3);
        border-top: 3px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin: 0 auto 20px auto;
    }
    
    .loader-text {
        font-size: 18px;
        font-weight: 500;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    
    .animate-on-scroll.animate-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    .is-invalid {
        border-color: #fa1c39 !important;
        box-shadow: 0 0 0 0.2rem rgba(227, 45, 49, 0.25) !important;
    }
`,l=document.createElement("style");l.textContent=b;document.head.appendChild(l);
