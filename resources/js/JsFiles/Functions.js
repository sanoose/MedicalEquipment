
import Swal from 'sweetalert2';
// export  function test () {
//         console.log(222);
// }
  
export function ShowSwal({ title, icon, text, confirmText, cancelText, preConfirm }) {
  Swal.fire({
    title: title || "عنوان افتراضي",
    icon: icon || 'info',
    text: text || '',
    showCancelButton: true,
    confirmButtonColor: 'green',
    cancelButtonColor: '#d33',
 
    confirmButtonText: confirmText || "نعم",
    cancelButtonText: cancelText || "إلغاء",
    showLoaderOnConfirm: true,
    preConfirm: preConfirm
  });
}

// export  function GetYears () {
//   const current_year = new Date().getFullYear();
//   const start_year = 1990;
//   const years = [] ;
//   for (let year = current_year; year >= start_year; year--) {
//       years.push(year);
//   }
//   return years ;
// }

 
// export function Back() {
//   window.history.back() ;
// }
 

// //  استخراج الصور فقط من الملفات  لاستعمالها في الايت بوكس
// export function FilterImages ( files , types) {
//   if (files.length > 0 )
//     return files.filter( (el , i ) => types[i] == 'image'  ) ;
//   else {
//      return [] ;
//   }
// }

// //  استخراج الصور فقط من المسارات  لاستعمالها في الايت بوكس
// export const  GetImagePaths = (paths)  => {
//   // Define valid image extensions
//   const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.bmp', '.tiff', '.webp', '.svg', '.ico', '.heic', '.avif'] ;

//   // Filter the paths array to include only image paths
//   if (paths != null  ) {
//       return paths.filter(path => {
//         const extension = path.substring(path.lastIndexOf('.')).toLowerCase();
//         return imageExtensions.includes(extension);
//     });
//   }
//   return  []   ;
// }

// //  استخراج الامتداد من الروابط وتحديد اذا كان الملف صورة ام نوع آخر 
// export  function MatchImageExtension (url) { 
// const extension = url.match(/\.(jpg|jpeg|png|mp4|pdf|xlsx|docx|rar|zip)$/i);
// return {
//   extension: extension ? extension[0].substring(1) : '',
//   is_image: extension ? /\.(jpg|jpeg|png)$/i.test(extension[0]) : false,
// };

// }


// export function  GetFileType (file , files_type) { 
//   if ( file.type.includes('image/')    )  
//      files_type.push( "image" ) ;
//   else if ( file.type.includes ('video/'))
//      files_type.push( "video" ) ;
//   else if ( file.type.includes ('/pdf'))
//      files_type.push( "pdf" ) ;
//   else if ( file.type.includes ('.document'))
//      files_type.push( "word" ) ;   
//   else if ( file.type.includes ('/x-zip-compressed'))
//      files_type.push( "zip" ) ;
//   else if ( file.type.includes ('.spreadsheetml.sheet'))
//      files_type.push( "xlsx" ) ;
//   else 
//      files_type.push( "file" ) ; 
// }
 


// const focusNext = (elem) => {
//   const currentIndex = Array.from(elem.form.elements).indexOf(elem);
//   elem.form.elements
//       .item(
//           currentIndex < elem.form.elements.length - 1 ? currentIndex + 1 : 0
//        )
//       .focus();
// };


