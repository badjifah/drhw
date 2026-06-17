(function(){'use strict';var D={init:function(){this.topbarScroll();this.mobileMenu();this.backToTop();this.scrollReveal();this.toastSystem();this.buttonRipple();this.alertDismiss();this.smoothScroll();this.confirmDialogs();this.formValidation();this.filePreview();this.counterAnimation();this.cardTilt();this.particles();this.cursorGlow();this.parallax();},topbarScroll:function(){var t=document.querySelector('.topbar');if(!t)return;var ticking=false;window.addEventListener('scroll',function(){if(!ticking){window.requestAnimationFrame(function(){t.classList.toggle('scrolled',window.scrollY>20);ticking=false});ticking=true}},{passive:true});},mobileMenu:function(){var toggle=document.getElementById('mobile-menu-toggle');var nav=document.getElementById('topbar-nav');if(!toggle||!nav)return;toggle.addEventListener('click',function(){nav.classList.toggle('open');var icon=toggle.querySelector('i');if(icon){icon.classList.toggle('fa-bars');icon.classList.toggle('fa-times')}});document.addEventListener('click',function(e){if(window.innerWidth>768){nav.classList.remove('open');return}if(!nav.contains(e.target)&&!toggle.contains(e.target)){nav.classList.remove('open');var icon=toggle.querySelector('i');if(icon){icon.classList.remove('fa-times');icon.classList.add('fa-bars')}}});nav.querySelectorAll('a').forEach(function(link){link.addEventListener('click',function(){if(window.innerWidth<=768){nav.classList.remove('open');var icon=toggle.querySelector('i');if(icon){icon.classList.remove('fa-times');icon.classList.add('fa-bars')}}})});},backToTop:function(){var btn=document.getElementById('back-to-top');if(!btn)return;window.addEventListener('scroll',function(){btn.classList.toggle('visible',window.scrollY>400)},{passive:true});btn.addEventListener('click',function(){window.scrollTo({top:0,behavior:'smooth'})});},scrollReveal:function(){var els=document.querySelectorAll('.reveal,.reveal-left,.reveal-right,.reveal-scale');if(!els.length)return;var observer=new IntersectionObserver(function(entries){entries.forEach(function(entry){if(entry.isIntersecting){entry.target.classList.add('visible');observer.unobserve(entry.target)}})},{threshold:0.1,rootMargin:'0px 0px -50px 0px'});els.forEach(function(el){observer.observe(el)});},toastSystem:function(){var self=this;if(!document.querySelector('.toast-container')){var c=document.createElement('div');c.className='toast-container';document.body.appendChild(c)}window.showToast=function(message,type){type=type||'info';var container=document.querySelector('.toast-container');if(!container)return;var icons={success:'fa-check-circle',danger:'fa-exclamation-circle',warning:'fa-exclamation-triangle',info:'fa-info-circle'};var toast=document.createElement('div');toast.className='toast toast-'+type;toast.innerHTML='<div class="toast-icon"><i class="fas '+(icons[type]||icons.info)+'"></i></div><span class="toast-text">'+self.escapeHtml(message)+'</span><button class="toast-close">&times;</button><div class="toast-progress"></div>';toast.querySelector('.toast-close').addEventListener('click',function(){self.dismissToast(toast)});container.appendChild(toast);setTimeout(function(){self.dismissToast(toast)},5000)};document.querySelectorAll('[data-toast]').forEach(function(el){var msg=el.getAttribute('data-toast');var type=el.getAttribute('data-toast-type')||'info';if(msg){setTimeout(function(){window.showToast(msg,type)},300)}el.remove()})},dismissToast:function(toast){if(!toast||toast.classList.contains('toast-out'))return;toast.classList.add('toast-out');setTimeout(function(){if(toast.parentNode)toast.parentNode.removeChild(toast)},300)},escapeHtml:function(text){var div=document.createElement('div');div.appendChild(document.createTextNode(text));return div.innerHTML},buttonRipple:function(){document.querySelectorAll('.btn').forEach(function(btn){btn.addEventListener('click',function(e){var rect=btn.getBoundingClientRect();var ripple=document.createElement('span');ripple.className='btn-ripple';var size=Math.max(rect.width,rect.height);ripple.style.width=ripple.style.height=size+'px';ripple.style.left=(e.clientX-rect.left-size/2)+'px';ripple.style.top=(e.clientY-rect.top-size/2)+'px';btn.appendChild(ripple);ripple.addEventListener('animationend',function(){ripple.remove()})})});},alertDismiss:function(){document.querySelectorAll('.alert-close').forEach(function(btn){btn.addEventListener('click',function(){var alert=btn.closest('.alert');if(!alert)return;alert.style.opacity='0';alert.style.transform='translateX(20px)';setTimeout(function(){alert.remove()},200)});setTimeout(function(){var alert=btn.closest('.alert');if(alert){alert.style.opacity='0';alert.style.transform='translateX(20px)';setTimeout(function(){alert.remove()},200)}},6000)})},smoothScroll:function(){document.querySelectorAll('a[href^="#"]').forEach(function(anchor){anchor.addEventListener('click',function(e){var href=anchor.getAttribute('href');if(!href||href==='#')return;var target=document.querySelector(href);if(target){e.preventDefault();target.scrollIntoView({behavior:'smooth',block:'start'})}})})},confirmDialogs:function(){document.querySelectorAll('[data-confirm]').forEach(function(el){el.addEventListener('click',function(e){if(!confirm(el.getAttribute('data-confirm'))){e.preventDefault()}})})},formValidation:function(){document.querySelectorAll('form[data-validate]').forEach(function(form){form.addEventListener('submit',function(e){var valid=true;form.querySelectorAll('[required]').forEach(function(field){if(!field.value.trim()){field.style.borderColor='var(--color-danger)';field.style.boxShadow='0 0 0 3px rgba(248,113,113,0.1)';valid=false}else{field.style.borderColor='';field.style.boxShadow=''}});if(!valid){e.preventDefault();var first=form.querySelector('[required]');if(first)first.focus()}})})},filePreview:function(){document.querySelectorAll('input[type="file"]').forEach(function(input){var previewId=input.getAttribute('data-preview');if(!previewId)return;var preview=document.getElementById(previewId);if(!preview)return;input.addEventListener('change',function(){if(input.files&&input.files[0]){var reader=new FileReader();reader.onload=function(e){preview.src=e.target.result;preview.classList.remove('hidden');preview.style.animation='fadeIn 0.3s ease'};if(input.files[0].type.startsWith('image/')){reader.readAsDataURL(input.files[0])}}})})},counterAnimation:function(){var counters=document.querySelectorAll('.stat-card-value');if(!counters.length)return;var observer=new IntersectionObserver(function(entries){entries.forEach(function(entry){if(entry.isIntersecting){var el=entry.target;var text=el.textContent.trim();var target=parseInt(text.replace(/[^0-9]/g,''),10);if(isNaN(target)){observer.unobserve(el);return}var suffix=text.replace(/[0-9]/g,'');var duration=1500;var start=performance.now();function update(now){var progress=Math.min((now-start)/duration,1);var eased=1-Math.pow(1-progress,3);var current=Math.round(eased*target);el.textContent=current.toLocaleString('fr-FR')+suffix;if(progress<1){requestAnimationFrame(update)}else{el.textContent=target.toLocaleString('fr-FR')+suffix;el.style.animation='popIn 0.4s var(--ease-spring)'}}requestAnimationFrame(update);observer.unobserve(el)}})},{threshold:0.5});counters.forEach(function(el){observer.observe(el)})},cardTilt:function(){if(window.innerWidth<768)return;document.querySelectorAll('.article-card,.service-card,.doc-card,.stat-card').forEach(function(card){card.addEventListener('mousemove',function(e){var rect=card.getBoundingClientRect();var x=e.clientX-rect.left;var y=e.clientY-rect.top;var centerX=rect.width/2;var centerY=rect.height/2;var rotateX=(y-centerY)/25;var rotateY=(centerX-x)/25;card.style.transform='perspective(800px) rotateX('+rotateX+'deg) rotateY('+rotateY+'deg) translateY(-6px)';card.style.transition='transform 0.1s ease'});card.addEventListener('mouseleave',function(){card.style.transform='';card.style.transition='all var(--transition-base) var(--ease-bounce)'})})},particles:function(){var hero=document.querySelector('.hero-section');if(!hero)return;for(var i=0;i<15;i++){var particle=document.createElement('div');particle.style.cssText='position:absolute;border-radius:50%;pointer-events:none;opacity:0;width:'+(Math.random()*8+3)+'px;height:'+(Math.random()*8+3)+'px;left:'+(Math.random()*100)+'%;top:'+(Math.random()*100)+'%;background:rgba(139,92,246,'+(Math.random()*0.3+0.1)+');animation:particleFloat '+(Math.random()*10+8)+'s linear infinite '+(Math.random()*5)+'s;';hero.querySelector('.hero-particles').appendChild(particle)}},cursorGlow:function(){if(window.innerWidth<768)return;var glow=document.createElement('div');glow.style.cssText='position:fixed;width:300px;height:300px;border-radius:50%;background:radial-gradient(circle,rgba(139,92,246,0.06) 0%,transparent 70%);pointer-events:none;z-index:0;transform:translate(-50%,-50%);transition:opacity 0.3s ease;';document.body.appendChild(glow);document.addEventListener('mousemove',function(e){glow.style.left=e.clientX+'px';glow.style.top=e.clientY+'px';glow.style.opacity='1'});document.addEventListener('mouseleave',function(){glow.style.opacity='0'})},parallax:function(){var hero=document.querySelector('.hero-section');if(!hero)return;window.addEventListener('scroll',function(){var scrolled=window.scrollY;var particles=hero.querySelectorAll('.hero-particle');particles.forEach(function(p,i){var speed=0.1*(i+1);p.style.transform='translateY('+scrolled*speed+'px)'})},{passive:true})}};if(document.readyState==='loading'){document.addEventListener('DOMContentLoaded',function(){D.init()})}else{D.init()}})();
/* ===== DARK MODE TOGGLE ===== */
(function(){
  var KEY='drh-theme';
  var html=document.documentElement;
  function apply(t){
    if(t==='dark'){html.setAttribute('data-theme','dark')}
    else if(t==='light'){html.setAttribute('data-theme','light')}
    else{html.removeAttribute('data-theme')}
    var btn=document.getElementById('theme-toggle');
    if(!btn)return;
    var icon=btn.querySelector('i');
    var isDark=html.getAttribute('data-theme')==='dark'||(html.getAttribute('data-theme')!=='light'&&window.matchMedia('(prefers-color-scheme:dark)').matches);
    if(icon){icon.className=isDark?'fas fa-sun':'fas fa-moon'}
    btn.setAttribute('aria-label',isDark?'Passer en mode clair':'Passer en mode sombre');
  }
  var saved=localStorage.getItem(KEY);
  if(saved)apply(saved);
  document.addEventListener('DOMContentLoaded',function(){
    apply(localStorage.getItem(KEY)||'auto');
    var btn=document.getElementById('theme-toggle');
    if(!btn)return;
    btn.addEventListener('click',function(){
      var isDark=html.getAttribute('data-theme')==='dark'||(html.getAttribute('data-theme')!=='light'&&window.matchMedia('(prefers-color-scheme:dark)').matches);
      var next=isDark?'light':'dark';
      localStorage.setItem(KEY,next);
      apply(next);
    });
  });
})();

/* ===== CONTACT FORM VALIDATION ===== */
(function(){
  document.addEventListener('DOMContentLoaded',function(){
    var form=document.querySelector('form[data-contact-form]');
    if(!form)return;
    function escHtml(s){var d=document.createElement('div');d.appendChild(document.createTextNode(s));return d.innerHTML}
    function showErr(input,msg){
      input.classList.add('is-invalid');input.classList.remove('is-valid');
      var old=input.parentNode.querySelector('.form-error');if(old)old.remove();
      var p=document.createElement('p');p.className='form-error';
      p.innerHTML='<i class="fas fa-exclamation-circle" aria-hidden="true"></i> '+escHtml(msg);
      input.parentNode.appendChild(p);
    }
    function clearErr(input){
      input.classList.remove('is-invalid');input.classList.add('is-valid');
      var old=input.parentNode.querySelector('.form-error');if(old)old.remove();
    }
    function validateField(f){
      var v=f.value.trim();
      if(f.hasAttribute('required')&&!v){showErr(f,'Ce champ est requis.');return false}
      if(f.type==='email'&&v&&!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v)){showErr(f,'Adresse email invalide.');return false}
      if(f.type==='tel'&&v&&!/^[\d\s\-\+\(\)]{5,20}$/.test(v)){showErr(f,'Format de téléphone invalide.');return false}
      if(v)clearErr(f);return true;
    }
    form.querySelectorAll('.form-input,.form-textarea').forEach(function(f){
      f.addEventListener('blur',function(){validateField(f)});
      f.addEventListener('input',function(){if(f.classList.contains('is-invalid'))validateField(f)});
    });
    form.addEventListener('submit',function(e){
      var ok=true;
      form.querySelectorAll('.form-input,.form-textarea').forEach(function(f){if(!validateField(f))ok=false});
      if(!ok){e.preventDefault();var first=form.querySelector('.is-invalid');if(first)first.focus();return}
      var btn=form.querySelector('button[type="submit"]');
      if(btn){
        var orig=btn.innerHTML;
        btn.innerHTML='<span class="btn-text">'+orig+'</span>';
        btn.classList.add('btn-loading');
      }
    });
  });
})();

/* ===== IMAGE LAZY LOADING FALLBACK ===== */
(function(){
  document.addEventListener('DOMContentLoaded',function(){
    document.querySelectorAll('img[loading="lazy"]').forEach(function(img){
      img.addEventListener('error',function(){
        var wrap=img.parentNode;
        img.style.display='none';
        if(wrap&&!wrap.querySelector('.img-fallback')){
          var d=document.createElement('div');d.className='img-fallback';
          d.innerHTML='<i class="fas fa-image" aria-hidden="true"></i>';
          wrap.appendChild(d);
        }
      });
    });
  });
})();
