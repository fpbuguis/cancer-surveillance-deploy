import{d as c,Q as W,l as q,T as X,m as g,p as N,o as p,e as f,b as t,u as l,w as n,F as V,Z as Y,a as r,g as u,f as M,q as y,v as b,h as w,t as x,n as P,k as h,c as ee}from"./app-42536e63.js";import{_ as se}from"./_plugin-vue_export-helper-c27b6911.js";import{_ as i}from"./InputError-d893a523.js";import{_ as o}from"./InputLabel-ff26f500.js";import{_ as ae}from"./PrimaryButton-cd95b478.js";import{_ as m}from"./TextInput-d9580142.js";import{A as le}from"./Alert-e9943a8b.js";import te from"./Layout-1c104a2a.js";import{d as re}from"./index-189aeacd.js";import"./DropdownLink-ed97a0d6.js";/* empty css                                                               */const de={key:0},ie={key:1},oe=["value"],ne=["value"],ue=["value"],me=["value"],pe={class:"flex items-center justify-center mt-4"},ce={__name:"PatientDetailsUpdatePage",setup(fe){const _=c(0),U=c(0),z=c(null),k=c(null),v=c({show:!1,message:"",type:""}),{url:C,props:G}=W();if(C){const d=new URL(C,window.location.origin);_.value=d.searchParams.get("userId")}else console.error("URL is undefined");const Z=async d=>{try{let e=await g.get(`/api/get-user/${d}`);U.value=e.data,console.log("User Details: ",U.value)}catch(e){console.error("Failed to fetch user:",e)}},H=async d=>{try{let e=await g.get(`/api/patient-profile/${d}`);return z.value=e.data,k.value=z.value.patient_id,console.log("Patient ID:",k.value),k.value}catch(e){console.error(`Failed to fetch patient with userId ${d}:`,e)}};q(async()=>{await Z(_.value),await H(_.value)});const s=X({firstname:"",middlename:"",lastname:"",email:"",contact_number:"",password:"",password_confirmation:"",birthday:"",birthplace:"",gender:"",marital_status:"",address_brgy:"",address_street:"",address_number:"",address_zipcode:"",region_id:null,province_id:null,city_id:null,municipality_id:null}),D=c([]),B=c([]),$=c([]),S=c([]);q(async()=>{const d=await g.get("/api/regions");D.value=d.data}),N(U,d=>{var e,a,L,A,R,T,O,j;d&&(s.firstname=d.firstname||"",s.middlename=d.middlename||"",s.lastname=d.lastname||"",s.email=d.email||"",s.contact_number=d.contact_number||"",s.birthday=d.birthday||"",s.birthplace=d.birthplace||"",s.gender=d.gender||"",s.marital_status=d.marital_status||"",s.address_brgy=((e=d.address)==null?void 0:e.address_brgy)||"",s.address_street=((a=d.address)==null?void 0:a.address_street)||"",s.address_number=((L=d.address)==null?void 0:L.address_number)||"",s.address_zipcode=((A=d.address)==null?void 0:A.address_zipcode)||"",s.region_id=((R=d.address)==null?void 0:R.region_id)||null,s.province_id=((T=d.address)==null?void 0:T.province_id)||null,s.city_id=((O=d.address)==null?void 0:O.city_id)||null,s.municipality_id=((j=d.address)==null?void 0:j.municipality_id)||null)},{immediate:!0}),N(()=>s.region_id,async d=>{if(d){const e=await g.get(`/api/provinces/${d}`);B.value=e.data,$.value=[],S.value=[]}}),N(()=>s.province_id,async d=>{if(d){const e=await g.get(`/api/cities/${d}`);$.value=e.data;const a=await g.get(`/api/municipalities/${d}`);S.value=a.data}});function I(){return/^09\d{9}$/.test(s.contact_number)?(s.errors.contact_number=null,!0):(s.errors.contact_number="Format must be in 09xxxxxxxxx.",!1)}let Q=G.auth.user.user_id;const F=c(null);let E=c(0);const J=async d=>{try{let e=await g.get(`/api/doctor-profile/${d}`);F.value=e.data,E.value=F.value.doctor_id,console.log(E.value)}catch(e){console.error(`Failed to fetch doctor with userId ${d}:`,e)}};q(async()=>{await J(Q)});const K=async()=>{if(v.value.show=!1,!!I())try{const d={lastname:s.lastname,firstname:s.firstname,middlename:s.middlename,email:s.email,contact_number:s.contact_number,gender:s.gender,marital_status:s.marital_status,birthday:s.birthday,birthplace:s.birthplace,address:{address_number:s.address_number,address_street:s.address_street,address_brgy:s.address_brgy,address_zipcode:s.address_zipcode,region_id:s.region_id,province_id:s.province_id,city_id:s.city_id,municipality_id:s.municipality_id}};(await g.put(`/api/update-patient-info/${k.value}`,d)).status===200&&(v.value={show:!0,message:"Success! Patient details updated.",type:"success"},re.Inertia.get("/search"))}catch(d){d.response&&d.response.data.errors?Object.keys(d.response.data.errors).forEach(e=>{s.errors[e]=d.response.data.errors[e]}):(console.error("An error occurred:",d),v(`An error occurred: ${d.message}`))}};return(d,e)=>(p(),f(V,null,[t(l(Y),{title:"Patient Enrollment"}),t(te,null,{default:n(()=>[e[43]||(e[43]=r("div",{class:"page-heading"},[r("h1",{class:"main-heading"},"PATIENT ENROLLMENT")],-1)),r("form",{onSubmit:e[19]||(e[19]=h((...a)=>d.submit&&d.submit(...a),["prevent"])),class:"form-container"},[r("div",null,[t(o,{for:"lastname"},{default:n(()=>e[20]||(e[20]=[u(" Last Name "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),t(m,{id:"lastname",modelValue:l(s).lastname,"onUpdate:modelValue":e[0]||(e[0]=a=>l(s).lastname=a),type:"text",class:"mt-1 block w-full",required:"",autofocus:"",autocomplete:"lastname"},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.lastname},null,8,["message"])]),r("div",null,[t(o,{for:"firstname"},{default:n(()=>e[21]||(e[21]=[u(" First Name "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),t(m,{id:"firstname",modelValue:l(s).firstname,"onUpdate:modelValue":e[1]||(e[1]=a=>l(s).firstname=a),type:"text",class:"mt-1 block w-full",autofocus:"",autocomplete:"firstname"},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.firstname},null,8,["message"])]),r("div",null,[t(o,{for:"middlename"},{default:n(()=>e[22]||(e[22]=[u(" Middle Name ")])),_:1}),t(m,{id:"middlename",modelValue:l(s).middlename,"onUpdate:modelValue":e[2]||(e[2]=a=>l(s).middlename=a),type:"text",class:"mt-1 block w-full",autocomplete:"middlename"},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.middlename},null,8,["message"])]),r("div",null,[t(o,{for:"email"},{default:n(()=>e[23]||(e[23]=[u(" Email Address "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),t(m,{id:"email",modelValue:l(s).email,"onUpdate:modelValue":e[3]||(e[3]=a=>l(s).email=a),type:"email",class:"mt-1 block w-full",required:"",autocomplete:"username"},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.email},null,8,["message"])]),r("div",null,[t(o,{for:"contact_number"},{default:n(()=>e[24]||(e[24]=[u(" Contact Number "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),t(m,{id:"contact_number",modelValue:l(s).contact_number,"onUpdate:modelValue":e[4]||(e[4]=a=>l(s).contact_number=a),type:"tel",class:"mt-1 block w-full",required:"",onInput:I,placeholder:"ex. 09xxxxxxxxx"},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.contact_number},null,8,["message"])]),_.value?M("",!0):(p(),f("div",de,[t(o,{for:"password"},{default:n(()=>e[25]||(e[25]=[u(" Password "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),t(m,{id:"password",modelValue:l(s).password,"onUpdate:modelValue":e[5]||(e[5]=a=>l(s).password=a),type:"password",class:"mt-1 block w-full",required:"",autocomplete:"new-password"},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.password},null,8,["message"])])),_.value?M("",!0):(p(),f("div",ie,[t(o,{for:"password_confirmation"},{default:n(()=>e[26]||(e[26]=[u(" Confirm Password "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),t(m,{id:"password_confirmation",modelValue:l(s).password_confirmation,"onUpdate:modelValue":e[6]||(e[6]=a=>l(s).password_confirmation=a),type:"password",class:"mt-1 block w-full",disabled:_.value,required:"",autocomplete:"new-password"},null,8,["modelValue","disabled"]),t(i,{class:"mt-2",message:l(s).errors.password_confirmation},null,8,["message"])])),r("div",null,[t(o,{for:"birthday"},{default:n(()=>e[27]||(e[27]=[u(" Birthday "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),t(m,{id:"birthday",modelValue:l(s).birthday,"onUpdate:modelValue":e[7]||(e[7]=a=>l(s).birthday=a),type:"date",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.birthday},null,8,["message"])]),r("div",null,[t(o,{for:"birthplace",value:"Birthplace"}),t(m,{id:"birthplace",modelValue:l(s).birthplace,"onUpdate:modelValue":e[8]||(e[8]=a=>l(s).birthplace=a),type:"text",class:"mt-1 block w-full"},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.birthplace},null,8,["message"])]),r("div",null,[t(o,{for:"gender",value:"Gender"}),y(r("select",{id:"gender","onUpdate:modelValue":e[9]||(e[9]=a=>l(s).gender=a),class:"mt-1 block w-full"},e[28]||(e[28]=[r("option",{value:"",disabled:""},"Select Gender",-1),r("option",{value:"male"},"Male",-1),r("option",{value:"female"},"Female",-1),r("option",{value:"other"},"Other",-1)]),512),[[b,l(s).gender]]),t(i,{class:"mt-2",message:l(s).errors.gender},null,8,["message"])]),r("div",null,[t(o,{for:"marital_status",value:"Marital Status"}),y(r("select",{id:"marital_status","onUpdate:modelValue":e[10]||(e[10]=a=>l(s).marital_status=a),class:"mt-1 block w-full"},e[29]||(e[29]=[r("option",{value:"",disabled:""},"Select Marital Status",-1),r("option",{value:"single"},"Single",-1),r("option",{value:"married"},"Married",-1)]),512),[[b,l(s).marital_status]]),t(i,{class:"mt-2",message:l(s).errors.marital_status},null,8,["message"])]),r("div",null,[t(o,{for:"region_id"},{default:n(()=>e[30]||(e[30]=[u(" Region "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),y(r("select",{id:"region_id","onUpdate:modelValue":e[11]||(e[11]=a=>l(s).region_id=a),class:"mt-1 block w-full",required:""},[e[31]||(e[31]=r("option",{value:"",disabled:""},"Select Region",-1)),(p(!0),f(V,null,w(D.value,a=>(p(),f("option",{key:a.region_id,value:a.region_id},x(a.region_name),9,oe))),128))],512),[[b,l(s).region_id]]),t(i,{class:"mt-2",message:l(s).errors.region_id},null,8,["message"])]),r("div",null,[t(o,{for:"province_id"},{default:n(()=>e[32]||(e[32]=[u(" Province "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),y(r("select",{id:"province_id","onUpdate:modelValue":e[12]||(e[12]=a=>l(s).province_id=a),class:"mt-1 block w-full",required:""},[e[33]||(e[33]=r("option",{value:"",disabled:""},"Select Province",-1)),(p(!0),f(V,null,w(B.value,a=>(p(),f("option",{key:a.province_id,value:a.province_id},x(a.province_name),9,ne))),128))],512),[[b,l(s).province_id]]),t(i,{class:"mt-2",message:l(s).errors.province_id},null,8,["message"])]),r("div",null,[t(o,{for:"city_id"},{default:n(()=>e[34]||(e[34]=[u(" City ")])),_:1}),y(r("select",{id:"city_id","onUpdate:modelValue":e[13]||(e[13]=a=>l(s).city_id=a),class:"mt-1 block w-full"},[e[35]||(e[35]=r("option",{value:"",disabled:""},"Select City",-1)),(p(!0),f(V,null,w($.value,a=>(p(),f("option",{key:a.city_id,value:a.city_id},x(a.city_name),9,ue))),128))],512),[[b,l(s).city_id]]),t(i,{class:"mt-2",message:l(s).errors.city_id},null,8,["message"])]),r("div",null,[t(o,{for:"municipality_id"},{default:n(()=>e[36]||(e[36]=[u(" Municipality ")])),_:1}),y(r("select",{id:"municipality_id","onUpdate:modelValue":e[14]||(e[14]=a=>l(s).municipality_id=a),class:"mt-1 block w-full"},[e[37]||(e[37]=r("option",{value:"",disabled:""},"Select Municipality",-1)),(p(!0),f(V,null,w(S.value,a=>(p(),f("option",{key:a.municipality_id,value:a.municipality_id},x(a.municipality_name),9,me))),128))],512),[[b,l(s).municipality_id]]),t(i,{class:"mt-2",message:l(s).errors.municipality_id},null,8,["message"])]),r("div",null,[t(o,{for:"address_brgy"},{default:n(()=>e[38]||(e[38]=[u(" Barangay "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),t(m,{id:"address_brgy",modelValue:l(s).address_brgy,"onUpdate:modelValue":e[15]||(e[15]=a=>l(s).address_brgy=a),type:"text",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.address_brgy},null,8,["message"])]),r("div",null,[t(o,{for:"address_number"},{default:n(()=>e[39]||(e[39]=[u(" Block/House Number "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),t(m,{id:"address_number",modelValue:l(s).address_number,"onUpdate:modelValue":e[16]||(e[16]=a=>l(s).address_number=a),type:"text",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.address_number},null,8,["message"])]),r("div",null,[t(o,{for:"address_street"},{default:n(()=>e[40]||(e[40]=[u(" Street "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),t(m,{id:"address_street",modelValue:l(s).address_street,"onUpdate:modelValue":e[17]||(e[17]=a=>l(s).address_street=a),type:"text",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.address_street},null,8,["message"])]),r("div",null,[t(o,{for:"address_zipcode"},{default:n(()=>e[41]||(e[41]=[u(" Zipcode "),r("span",{class:"red-asterisk"},"*",-1)])),_:1}),t(m,{id:"address_zipcode",modelValue:l(s).address_zipcode,"onUpdate:modelValue":e[18]||(e[18]=a=>l(s).address_zipcode=a),type:"text",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),t(i,{class:"mt-2",message:l(s).errors.address_zipcode},null,8,["message"])]),r("div",pe,[t(ae,{class:P(["ms-4",{"opacity-25":l(s).processing}]),disabled:l(s).processing,onClick:K},{default:n(()=>e[42]||(e[42]=[u(" Update Details ")])),_:1},8,["class","disabled"])])],32),v.value.show?(p(),ee(le,{key:0,message:v.value.message,type:v.value.type},null,8,["message","type"])):M("",!0)]),_:1})],64))}},Se=se(ce,[["__scopeId","data-v-2ddd3006"]]);export{Se as default};