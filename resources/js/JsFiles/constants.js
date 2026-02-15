 
export const role = {
    '1': 'سوبر أدمن',
    '2': ' أدمن ',
    '3': 'مستخدم',
 
  };
 
 

 export const client_type =  {
    '1': 'منشأة صحية',
    '2': ' شركة ',
 
 
  }; 
 
 export const client_subtype =  {
    '1': 'مستشفى',
    '2': ' مصحة ',
    '3': 'جهة عامة',
    '4': 'جهة خاصة',
 
  }; 

  export const supply_order_status = {
  '1': 'قيد الانتظار',
  '2': 'قبول',
  '3': 'رفض',
};

export const equipment_order_status = {
  '1': 'قيد الانتظار',
  '2': 'قبول',
  '3': 'رفض',
};

export const maintenance_status = {
  '1': 'قيد الانتظار',
  '2': 'قبول',
  '3': 'رفض',
};


export const insurance_years = [1, 2, 3];

export const made_years = Array.from({ length: new Date().getFullYear() - 1999 }, (_, i) => 2000 + i);


export const  additional_fee_per_year = 30 ;

export const  id_types  =  {
  1: 'بطاقة شخصية',
  2: 'جواز سفر',
};

 export const  claim_type   =  {
  1: 'حادث  تصادم',
  2: ' ضرر غير ناتج عن حادث ',
};
 export const  claim_status   =  {
  1: 'مطالبة جديدة',
  2: 'قيد  المعالجة',
  3: 'تم القبول',
  4: 'تم الرفض',
};
  