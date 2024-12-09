import{Q as q,d as p,T as R,s as $,l as A,D,o as V,c as C,w as f,m as b,a as t,b as a,g as v,u as l,q as k,A as _,n as N,k as E,f as I}from"./app-42536e63.js";import{_ as m}from"./InputError-d893a523.js";import{_ as d}from"./InputLabel-ff26f500.js";import{_ as i}from"./TextInput-d9580142.js";import P from"./Layout-1c104a2a.js";import{_ as L}from"./PrimaryButton-cd95b478.js";import{d as B}from"./index-189aeacd.js";import{A as F}from"./Alert-e9943a8b.js";import{_ as M}from"./_plugin-vue_export-helper-c27b6911.js";import"./DropdownLink-ed97a0d6.js";/* empty css                                                               */const O={class:"mt-1 flex flex-col gap-2"},j={class:"inline-flex items-center"},z={class:"inline-flex items-center mt-1"},Q={class:"inline-flex items-center mt-1"},W={class:"inline-flex items-center mt-1"},G={class:"flex items-center justify-center mt-4"},J={__name:"SchedConsultPage",setup(K){const{url:w,props:T}=q(),h=w.split("/");let n=h[h.length-1];n=parseInt(n,10);let x=T.auth.user.user_id;const y=p(null);let g=p(null);const c=p(null),r=p({show:!1,message:"",type:""}),s=R({lastname:"",email:"",checkupRequest_date:"",checkupConfirmed_date:"",checkup_startTime:"",checkup_endTime:"",checkup_status:"",patient_id:n,doctor_id:$(()=>g.value)}),H=async u=>{try{console.log(u);let e=await b.get(`/api/get-patient/${u}`);c.value=e.data,s.lastname=c.value.user.lastname,s.email=c.value.user.email}catch(e){console.error("Failed to fetch patient:",e)}},U=async u=>{console.log(u);try{let e=await b.get(`/api/doctor-profile/${u}`);y.value=e.data,g.value=y.value.doctor_id,console.log(g.value)}catch(e){console.error(`Failed to fetch doctor with userId ${u}:`,e)}};A(async()=>{await H(n),await U(x)}),D(async()=>{console.log(n),console.log(c),console.log(s)});const S=()=>{console.log(s),s.post("/api/create-schedule-checkup",{onSuccess:()=>{r.value={show:!0,message:"Consultation Schedule Added!",type:"success"},B.Inertia.visit(`/schedconsult/${n}`)},onError:u=>{console.log("WAHHHHHHHHHHHHH"),r.value={show:!0,message:"Consultation Schedule Failed!",type:"error"},u.patient_id?error.value=u.patient_id[0]:console.log(u)}})};return(u,e)=>(V(),C(P,null,{default:f(()=>[e[21]||(e[21]=t("div",{class:"page-heading"},[t("h1",{class:"main-heading"},"SCHEDULE CONSULTATION")],-1)),t("form",{onSubmit:E(S,["prevent"]),class:"form-container"},[t("div",null,[a(d,{for:"lastname"},{default:f(()=>e[10]||(e[10]=[v(" Last Name "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),a(i,{id:"lastname",modelValue:l(s).lastname,"onUpdate:modelValue":e[0]||(e[0]=o=>l(s).lastname=o),type:"text",class:"mt-1 block w-full",autofocus:"",autocomplete:"lastname",disabled:l(n)},null,8,["modelValue","disabled"]),a(m,{class:"mt-2",message:l(s).errors.lastname},null,8,["message"])]),t("div",null,[a(d,{for:"email"},{default:f(()=>e[11]||(e[11]=[v(" Email Address "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),a(i,{id:"email",modelValue:l(s).email,"onUpdate:modelValue":e[1]||(e[1]=o=>l(s).email=o),type:"email",class:"mt-1 block w-full",autocomplete:"username",disabled:l(n)},null,8,["modelValue","disabled"]),a(m,{class:"mt-2",message:l(s).errors.email},null,8,["message"])]),t("div",null,[a(d,{for:"checkupRequest_date",value:"Checkup Request Date"}),a(i,{id:"checkupRequest_date",modelValue:l(s).checkupRequest_date,"onUpdate:modelValue":e[2]||(e[2]=o=>l(s).checkupRequest_date=o),type:"date",class:"mt-1 block w-full"},null,8,["modelValue"]),a(m,{class:"mt-2",message:l(s).errors.checkupRequest_date},null,8,["message"])]),t("div",null,[a(d,{for:"checkupConfirmed_date",value:"Checkup Confirmed Date"}),a(i,{id:"checkupConfirmed_date",modelValue:l(s).checkupConfirmed_date,"onUpdate:modelValue":e[3]||(e[3]=o=>l(s).checkupConfirmed_date=o),type:"date",class:"mt-1 block w-full"},null,8,["modelValue"]),a(m,{class:"mt-2",message:l(s).errors.checkupConfirmed_date},null,8,["message"])]),t("div",null,[a(d,{for:"checkup_startTime",value:"Checkup Start Time"}),a(i,{id:"checkup_startTime",modelValue:l(s).checkup_startTime,"onUpdate:modelValue":e[4]||(e[4]=o=>l(s).checkup_startTime=o),type:"time",class:"mt-1 block w-full"},null,8,["modelValue"]),a(m,{class:"mt-2",message:l(s).errors.checkup_startTime},null,8,["message"])]),t("div",null,[a(d,{for:"checkup_endTime",value:"Checkup End Time"}),a(i,{id:"checkup_endTime",modelValue:l(s).checkup_endTime,"onUpdate:modelValue":e[5]||(e[5]=o=>l(s).checkup_endTime=o),type:"time",class:"mt-1 block w-full"},null,8,["modelValue"]),a(m,{class:"mt-2",message:l(s).errors.checkup_endTime},null,8,["message"])]),t("div",O,[a(d,{for:"checkup_status",value:"Checkup Status"}),t("label",j,[k(t("input",{type:"radio",name:"checkup_status",value:"request","onUpdate:modelValue":e[6]||(e[6]=o=>l(s).checkup_status=o),class:"form-radio"},null,512),[[_,l(s).checkup_status]]),e[12]||(e[12]=t("span",{class:"custom-radio"},null,-1)),e[13]||(e[13]=t("span",{class:"ml-2"},"Requested",-1))]),t("label",z,[k(t("input",{type:"radio",name:"checkup_status",value:"confirmed","onUpdate:modelValue":e[7]||(e[7]=o=>l(s).checkup_status=o),class:"form-radio"},null,512),[[_,l(s).checkup_status]]),e[14]||(e[14]=t("span",{class:"custom-radio"},null,-1)),e[15]||(e[15]=t("span",{class:"ml-2"},"Confirmed",-1))]),t("label",Q,[k(t("input",{type:"radio",name:"checkup_status",value:"done","onUpdate:modelValue":e[8]||(e[8]=o=>l(s).checkup_status=o),class:"form-radio"},null,512),[[_,l(s).checkup_status]]),e[16]||(e[16]=t("span",{class:"custom-radio"},null,-1)),e[17]||(e[17]=t("span",{class:"ml-2"},"Done",-1))]),t("label",W,[k(t("input",{type:"radio",name:"checkup_status",value:"cancelled","onUpdate:modelValue":e[9]||(e[9]=o=>l(s).checkup_status=o),class:"form-radio"},null,512),[[_,l(s).checkup_status]]),e[18]||(e[18]=t("span",{class:"custom-radio"},null,-1)),e[19]||(e[19]=t("span",{class:"ml-2"},"Cancelled",-1))])]),t("div",G,[a(L,{class:N(["ms-4",{"opacity-25":l(s).processing}]),disabled:l(s).processing},{default:f(()=>e[20]||(e[20]=[v(" Submit ")])),_:1},8,["class","disabled"])])],32),r.value.show?(V(),C(F,{key:0,message:r.value.message,type:r.value.type},null,8,["message","type"])):I("",!0)]),_:1}))}},de=M(J,[["__scopeId","data-v-dde5dfc3"]]);export{de as default};