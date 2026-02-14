import './bootstrap';
import '../css/app.css';

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import 'vue3-toastify/dist/index.css'; 

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// el +
import ElementPlus from 'element-plus';
import 'element-plus/dist/index.css';

// Font Awesome
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { faEnvelope, faUser, faLock   , faMobileRetro  , faCircleCheck     , faGauge  , faFileDownload  , faStar as faStarSolid   , faTrashCan  , faXmark   , faArrowRight  
   ,  faClipboardCheck ,  faCheckDouble  ,  faPlugCircleCheck  ,  faCheck  , faPaperPlane  , faBuilding  , faLink   , faCommentAlt  , faArrowLeft     , faSearch , faHand  ,  faUsers , 
   faGlobe,     faCheckCircle,     faTimesCircle,     faHourglassHalf,    faIdCard   ,     faFileLines  ,    faFileAlt  ,    faFileText ,
   faChartBar, faChartPie,    faCalendarAlt, faFlag, faUserClock ,     faHome,    faThLarge,    faEye,    faReply,    faChevronDown,    faFileInvoiceDollar , faGear , faTriangleExclamation   , 
  faMapMarkedAlt  , faMapLocation  , faBriefcase , faGraduationCap ,  faChalkboard,     faUserGraduate,         faUsersBetweenLines,     faLaptopCode     , faPlus  , faEdit 
  , faEllipsisVertical  , faPrint  ,  faFileCircleQuestion,  faChartLine, faCarSide, faCity, faTags, faFileSignature, faPalette, faImage  ,
 
  faFileContract,
 
  faFolderOpen,
  faCarCrash,
  faTools,
 
 
  } from '@fortawesome/free-solid-svg-icons'
import { faTwitter, faLinkedinIn, faFacebookF, faInstagram, faYoutube   , faWhatsapp  } from '@fortawesome/free-brands-svg-icons' 
import { faStar as faStarRegular } from '@fortawesome/free-regular-svg-icons'
 



library.add(faEnvelope, faUser , faUsers , faLock , faMobileRetro , faCircleCheck , faFileDownload  , faGraduationCap  , faArrowRight    , faBuilding   , faLink  , faCommentAlt  , faArrowLeft   ,    faChartBar, faChartPie,    faFileLines  ,
    faFileAlt  ,   faHome, faGear , faTriangleExclamation   , faMapMarkedAlt , faMapLocation ,  faBriefcase ,   faChalkboard,     faUserGraduate,         faUsersBetweenLines,     faLaptopCode    ,
    faThLarge,     faReply,     faChevronDown,     faFileInvoiceDollar ,     faEye,     faFileText ,  faPlus , faEdit ,  faEllipsisVertical , faPrint ,   faFileCircleQuestion,  faChartLine, faCarSide, faCity, faTags, faFileSignature, faPalette,
    faCalendarAlt, faFlag, faUserClock    , faSearch  , faWhatsapp , faHand  , faGlobe, faCheckCircle, faTimesCircle, faHourglassHalf, faIdCard , faImage  ,   faFileContract, faFolderOpen, faCarCrash, faTools
   , faTwitter, faLinkedinIn, faFacebookF, faInstagram, faYoutube , faGauge ,  faStarSolid , faStarRegular  , faTrashCan     ,faXmark   , faPaperPlane ,  faClipboardCheck ,  faCheckDouble  ,  faPlugCircleCheck  ,  faCheck   ) ; 
 





 

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
 
    title: (title) => `${title}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(VueSweetalert2)
            .use(ElementPlus)
            .component('fa', FontAwesomeIcon) // 👈 هذا هو السطر المهم
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
