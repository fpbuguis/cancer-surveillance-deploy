import{Q as _e,d as c,T as pe,s as I,l as ge,m as x,p as ve,o as _,e as p,b as i,u as a,w as u,F as k,Z as ye,a as t,g as m,c as P,f as b,h,q as y,A as q,t as S,v as w,k as Q,x as B,B as fe,n as Z}from"./app-42536e63.js";import{_ as n}from"./InputError-d893a523.js";import{_ as d}from"./InputLabel-ff26f500.js";import{_ as J}from"./PrimaryButton-cd95b478.js";import{_ as f}from"./TextInput-d9580142.js";import ce from"./Layout-1c104a2a.js";import{A as be}from"./Alert-e9943a8b.js";import"./index-189aeacd.js";import{_ as ke}from"./_plugin-vue_export-helper-c27b6911.js";import"./DropdownLink-ed97a0d6.js";/* empty css                                                               */const he={class:"mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2"},Se=["value","onUpdate:modelValue"],Ve={class:"ml-2"},xe={key:0},we={class:"mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2"},Ue=["value"],Ie={class:"ml-2"},Pe={class:"mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2"},De=["value"],qe={class:"ml-2"},Be=["value"],Le={key:0,class:"dropdown-list"},Me=["onMousedown"],Oe={class:"mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2"},Ee={class:"inline-flex items-center"},Ce={class:"mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2"},Ne={class:"inline-flex items-center"},Te=["value"],ze={class:"mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2"},Ae=["value"],Fe={class:"ml-2"},He={key:1},$e={key:2},Ge={class:"mt-1 mb-2 grid grid-cols-1 md:grid-cols-2 gap-2"},Re=["value"],je={class:"ml-2"},Ke={key:0},We={class:"mt-1 grid grid-cols-1 md:grid-cols-2 gap-2"},Ye=["value"],Qe={class:"ml-2"},Ze={class:"flex items-center justify-center mt-4"},Je={__name:"DiseaseProfilePage",setup(Xe){const{url:X}=_e(),E=X.split("/");let U=E[E.length-1];U=parseInt(U,10),console.log("Patient ID:",U);const L=c([]),C=c([]),N=c([]),T=c([]),z=c([]),V=c({show:!1,message:"",type:""}),s=pe({lastname:"",email:"",primary_site:null,custom_primary_site:"",diagnosis_date:"",diagnosis_basis:null,laterality:null,pathology:null,hist_tumor_extension:!1,hist_tumor_grade:"",hist_nodepositive:"",hist_nodeharvest:"",hist_negmargins:!1,hist_stage:"",extent_of_disease:null,tumor_size:"",nodal_involvement:"",metastatic_site:"",other_metastatic_site:"",metastatic_notes:"",multiple_primaries:"",other_primary_sites:[],additional_primary_site:"",t_stage:"",n_stage:"",m_stage:"",g_stage:"",grade:"",stage:"",stage_type:"",full_diagnosis:"",disease_status:[],errors:{primary_site:null,multiple_primaries:null,other_primary_sites:null}}),ee=async()=>{try{const r=await x.get("/api/disease/options");L.value=r.data.primarySites}catch(r){console.error("Error fetching body sites:",r)}},se=async()=>{try{const r=await x.get("/api/disease/options");C.value=r.data.bases}catch(r){console.error("Error fetching diagnosis basis:",r)}},te=async()=>{try{const r=await x.get("/api/disease/options");N.value=r.data.lateralities}catch(r){console.error("Error fetching laterality:",r)}},ae=async()=>{try{const r=await x.get("/api/disease/options");T.value=r.data.diseaseExtents}catch(r){console.error("Error fetching extent of disease:",r)}},A=c(""),le=c(null),F=c(!1),H=I(()=>histology.filter(r=>r.toLowerCase().includes(A.value.toLowerCase()))),ie=r=>{A.value=r,le.value=r,s.histology_id=r,F.value=!1},oe=["Distant Lymph Nodes","Bone","Liver","Lung","Brain","Ovary","Skin","Intestine","Other","Unknown"],$={mets_bone:"Bone",mets_brain:"Brain",mets_distantLN:"Distant Lymph Nodes",mets_intestine:"Intestine",mets_liver:"Liver",mets_lung:"Lung",mets_others:"Other",mets_ovary:"Ovary",mets_skin:"Skin",mets_unknown:"Unknown"},re=["Alive","Recurrent","With symptoms","Curative","Metastatic"],G=c(null),D=c({lastname:"",email:""}),o=c({}),ne=async()=>{try{let r=await x.get("/api/all-patients");G.value=r.data;const e=G.value.find(l=>l.patient_id===U);if(console.log("Found patient:",e),e){const l=e.user;D.value.lastname=l.lastname,D.value.email=l.email,s.lastname=D.value.lastname,s.email=D.value.email}}catch(r){console.error("Failed to fetch patient info:",r)}},de=async()=>{try{let r=await x.get(`/api/disease/${U}/profile`);o.value=r.data,console.log("Disease Profile:",o.value),s.primary_site=o.value.disease_primary_site.body_site_name,s.custom_primary_site=o.value.disease_otherPrimarySite,s.diagnosis_date=o.value.disease_diagnosisDate,s.diagnosis_basis=o.value.disease_basis.basis_name,s.laterality=o.value.disease_laterality.laterality_name,s.pathology=o.value.disease_histology.pathology.pathology_id,s.hist_tumor_extension=o.value.disease_histology.histo_tumorExtension===1,s.hist_tumor_grade=o.value.disease_histology.histo_tumorGrade,s.hist_nodepositive=o.value.disease_histology.histo_nodePositive,s.hist_nodeharvest=o.value.disease_histology.histo_nodeHarvest,s.hist_negmargins=o.value.disease_histology.histo_negativeMargins===1,s.hist_stage=o.value.disease_histology.histo_stage,s.extent_of_disease=o.value.disease_extent.extent_name,s.tumor_size=o.value.disease_tumorSize,s.nodal_involvement=o.value.disease_lymphNode;const e=Object.keys($).find(g=>o.value.disease_metastatic_site[g]===1);e&&(s.metastatic_site=$[e]),s.other_metastatic_site=o.value.disease_metastatic_site.mets_others_site,s.metastatic_notes=o.value.disease_metastatic_site.mets_notes,s.multiple_primaries=o.value.disease_multiplePrimary,s.other_primary_sites=[];const l={Blood:o.value.disease_other_primary_site.op_Blood,Brain:o.value.disease_other_primary_site.op_Brain,Breast:o.value.disease_other_primary_site.op_Breast,Colon:o.value.disease_other_primary_site.op_Colon,"Corpus Uteri":o.value.disease_other_primary_site.op_CorpusUteri,Esophagus:o.value.disease_other_primary_site.op_Esophagus,"Gall Bladder":o.value.disease_other_primary_site.op_GallBladder,Kidney:o.value.disease_other_primary_site.op_Kidney,Liver:o.value.disease_other_primary_site.op_Liver,Lung:o.value.disease_other_primary_site.op_Lung,Nasopharynx:o.value.disease_other_primary_site.op_Nasopharynx,"Oral Cavity":o.value.disease_other_primary_site.op_OralCavity,Others:o.value.disease_other_primary_site.op_Others,Ovary:o.value.disease_other_primary_site.op_Ovary,Pancreas:o.value.disease_other_primary_site.op_Pancreas,Prostate:o.value.disease_other_primary_site.op_Prostate,Rectum:o.value.disease_other_primary_site.op_Rectum,Skin:o.value.disease_other_primary_site.op_Skin,Stomach:o.value.disease_other_primary_site.op_Stomach,Testis:o.value.disease_other_primary_site.op_Testis,Thyroid:o.value.disease_other_primary_site.op_Thyroid,"Urinary Bladder":o.value.disease_other_primary_site.op_UrinaryBladder,"Uterine Cervix":o.value.disease_other_primary_site.op_UterineCervix};for(const[g,O]of Object.entries(l))O===1&&s.other_primary_sites.push(g);s.additional_primary_site=o.value.disease_other_primary_site.op_Others_Specify,s.t_stage=o.value.disease_t_stage,s.n_stage=o.value.disease_n_stage,s.m_stage=o.value.disease_m_stage,s.g_stage=o.value.disease_g_stage,s.grade=o.value.disease_grade,s.stage=o.value.disease_stage,s.stage_type=o.value.disease_stageType,s.full_diagnosis=o.value.disease_fullDiagnosis,s.disease_status=[];const v={Alive:o.value.disease_status.dxStatus_alive,Curative:o.value.disease_status.dxStatus_curative,Metastatic:o.value.disease_status.dxStatus_metastatic,Recurrent:o.value.disease_status.dxStatus_recurrence,"With symptoms":o.value.disease_status.dxStatus_symptoms};for(const[g,O]of Object.entries(v))O===1&&s.disease_status.push(g)}catch(r){console.error("Failed to fetch patient disease profile:",r)}};ge(async()=>{await ne(),await de(),await ee(),await se(),await te(),await ae();try{const[r]=await Promise.all([x.get("/api/pathologies")]);z.value=r.data}catch(r){console.error("Error loading initial data:",r)}});const M=c(0),R=I(()=>s.primary_site!==null&&s.primary_site!==""),j=I(()=>s.diagnosis_basis!==null&&s.diagnosis_basis!==""),K=I(()=>s.laterality!==null&&s.laterality!==""),W=I(()=>s.metastatic_site!==null&&s.metastatic_site!==""),Y=I(()=>s.disease_status.length>0),ue=()=>{let r=!0;(!R.value||!j.value||!K.value||!W.value||!Y.value)&&(r=!1);for(const e in s.errors)if(s.errors[e]){r=!1;break}return r},me=async()=>{if(V.value.show=!1,!!ue())try{await s.post("/api/create-disease-record",{onSuccess:()=>{M.value===1?(V.value={show:!0,message:"Success! Disease profile submitted.",type:"success"},s.reset()):(V.value={show:!0,message:"Success! Disease profile submitted.",type:"success"},window.location.href=`/treatment-history/${U}`,console.log("Form submitted successfully."))},onError:r=>{V.value={show:!0,message:"Error! Please try sbumitting again.",type:"error"},r.patient_id?error.value=r.patient_id[0]:console.log(r)}})}catch(r){console.error("Submission failed:",r)}};return ve(()=>[s.other_primary_sites,s.primary_site],([r,e])=>{s.multiple_primaries==="1"&&r.length!==1?s.errors.other_primary_sites="Select only one.":s.multiple_primaries==="2"&&r.length!==2?s.errors.other_primary_sites="Select only two.":s.errors.other_primary_sites=null}),(r,e)=>(_(),p(k,null,[i(a(ye),{title:"Disease Profile"}),i(ce,null,{default:u(()=>[e[76]||(e[76]=t("div",{class:"page-heading"},[t("h1",{class:"main-heading"},"DISEASE PROFILE")],-1)),t("form",{onSubmit:Q(me,["prevent"]),class:"form-container"},[t("div",null,[i(d,{for:"lastname"},{default:u(()=>e[33]||(e[33]=[m(" Last Name "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"lastname",modelValue:a(s).lastname,"onUpdate:modelValue":e[0]||(e[0]=l=>a(s).lastname=l),type:"text",class:"mt-1 block w-full",autofocus:"",autocomplete:"lastname",required:""},null,8,["modelValue"]),i(n,{class:"mt-2",message:a(s).errors.lastname},null,8,["message"])]),t("div",null,[i(d,{for:"email"},{default:u(()=>e[34]||(e[34]=[m(" Email Address "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"email",modelValue:a(s).email,"onUpdate:modelValue":e[1]||(e[1]=l=>a(s).email=l),type:"email",class:"mt-1 block w-full",autocomplete:"username",required:""},null,8,["modelValue"]),i(n,{class:"mt-2",message:a(s).errors.email},null,8,["message"])]),t("div",null,[i(d,{for:"primary_site"},{default:u(()=>e[35]||(e[35]=[m(" Primary Site "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),R.value?b("",!0):(_(),P(n,{key:0,message:"Please select one."})),t("div",he,[(_(!0),p(k,null,h(L.value.filter(l=>l.body_site_name!=="All"&&l.body_site_name!=="Negative"),(l,v)=>(_(),p("label",{key:v,class:"inline-flex items-center"},[y(t("input",{type:"radio",name:"primary_site",value:l.body_site_name,"onUpdate:modelValue":g=>a(s).primary_site=g,class:"form-radio",required:""},null,8,Se),[[q,a(s).primary_site]]),e[36]||(e[36]=t("span",{class:"custom-radio"},null,-1)),t("span",Ve,S(l.body_site_name),1)]))),128))]),i(n,{message:a(s).errors.primary_site},null,8,["message"])]),a(s).primary_site==="Others"?(_(),p("div",xe,[i(d,{for:"custom_primary_site"},{default:u(()=>e[37]||(e[37]=[m(" Specify Primary Site "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"custom_primary_site",modelValue:a(s).custom_primary_site,"onUpdate:modelValue":e[2]||(e[2]=l=>a(s).custom_primary_site=l),type:"text",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.custom_primary_site},null,8,["message"])])):b("",!0),t("div",null,[i(d,{for:"diagnosis_date"},{default:u(()=>e[38]||(e[38]=[m(" Date of Diagnosis "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"diagnosis_date",modelValue:a(s).diagnosis_date,"onUpdate:modelValue":e[3]||(e[3]=l=>a(s).diagnosis_date=l),type:"date",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{class:"mt-2",message:a(s).errors.diagnosis_date},null,8,["message"])]),t("div",null,[i(d,{for:"diagnosis_basis"},{default:u(()=>e[39]||(e[39]=[m(" Basis of Diagnosis "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),j.value?b("",!0):(_(),P(n,{key:0,message:"Please select one."})),t("div",we,[(_(!0),p(k,null,h(C.value,(l,v)=>(_(),p("label",{key:v,class:"inline-flex items-center"},[y(t("input",{type:"radio",name:"diagnosis_basis",value:l.basis_name,"onUpdate:modelValue":e[4]||(e[4]=g=>a(s).diagnosis_basis=g),class:"form-radio",required:""},null,8,Ue),[[q,a(s).diagnosis_basis]]),e[40]||(e[40]=t("span",{class:"custom-radio"},null,-1)),t("span",Ie,S(l.basis_name),1)]))),128))]),i(n,{message:a(s).errors.diagnosis_basis},null,8,["message"])]),t("div",null,[i(d,{for:"laterality"},{default:u(()=>e[41]||(e[41]=[m(" Laterality "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),K.value?b("",!0):(_(),P(n,{key:0,message:"Please select one."})),t("div",Pe,[(_(!0),p(k,null,h(N.value,(l,v)=>(_(),p("label",{key:v,class:"inline-flex items-center"},[y(t("input",{type:"radio",name:"laterality",value:l.laterality_name,"onUpdate:modelValue":e[5]||(e[5]=g=>a(s).laterality=g),class:"form-radio",required:""},null,8,De),[[q,a(s).laterality]]),e[42]||(e[42]=t("span",{class:"custom-radio"},null,-1)),t("span",qe,S(l.laterality_name),1)]))),128))]),i(n,{class:"mt-2",message:a(s).errors.laterality},null,8,["message"])]),t("div",null,[i(d,{for:"histo_pathology"},{default:u(()=>e[43]||(e[43]=[m(" Histology "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),y(t("select",{id:"pathology","onUpdate:modelValue":e[6]||(e[6]=l=>a(s).pathology=l),class:"mt-1 block w-full",required:""},[e[44]||(e[44]=t("option",{value:"",disabled:""},"Select pathology",-1)),(_(!0),p(k,null,h(z.value,l=>(_(),p("option",{key:l.pathology_id,value:l.pathology_id},S(l.term),9,Be))),128))],512),[[w,a(s).pathology]]),F.value&&H.value.length>0?(_(),p("ul",Le,[(_(!0),p(k,null,h(H.value,(l,v)=>(_(),p("li",{key:v,onMousedown:Q(g=>ie(l),["prevent"]),class:"dropdown-item"},S(l),41,Me))),128))])):b("",!0),i(n,{class:"mt-2",message:a(s).errors.histo_pathology},null,8,["message"])]),t("div",null,[i(d,{for:"hist_tumor_extension"},{default:u(()=>e[45]||(e[45]=[m(" Tumor Extension ")])),_:1}),t("div",Oe,[t("label",Ee,[y(t("input",{type:"checkbox","onUpdate:modelValue":e[7]||(e[7]=l=>a(s).hist_tumor_extension=l),class:"form-checkbox"},null,512),[[B,a(s).hist_tumor_extension]]),e[46]||(e[46]=t("span",{class:"ml-2"},"Yes",-1))])]),i(n,{message:a(s).errors.hist_tumor_extension},null,8,["message"])]),t("div",null,[i(d,{for:"hist_tumor_grade"},{default:u(()=>e[47]||(e[47]=[m(" Tumor Grade "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"hist_tumor_grade",modelValue:a(s).hist_tumor_grade,"onUpdate:modelValue":e[8]||(e[8]=l=>a(s).hist_tumor_grade=l),type:"number",step:"1",min:"0",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.hist_tumor_grade},null,8,["message"])]),t("div",null,[i(d,{for:"hist_nodepositive"},{default:u(()=>e[48]||(e[48]=[m(" Node Positive "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"hist_nodepositive",modelValue:a(s).hist_nodepositive,"onUpdate:modelValue":e[9]||(e[9]=l=>a(s).hist_nodepositive=l),type:"number",step:"1",min:"0",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.hist_nodepositive},null,8,["message"])]),t("div",null,[i(d,{for:"hist_nodeharvest"},{default:u(()=>e[49]||(e[49]=[m(" Node Harvest "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"hist_nodeharvest",modelValue:a(s).hist_nodeharvest,"onUpdate:modelValue":e[10]||(e[10]=l=>a(s).hist_nodeharvest=l),type:"number",step:"1",min:"0",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.hist_nodeharvest},null,8,["message"])]),t("div",null,[i(d,{for:"hist_negmargins",value:"Negative Margins"}),t("div",Ce,[t("label",Ne,[y(t("input",{type:"checkbox","onUpdate:modelValue":e[11]||(e[11]=l=>a(s).hist_negmargins=l),class:"form-checkbox"},null,512),[[B,a(s).hist_negmargins]]),e[50]||(e[50]=t("span",{class:"ml-2"},"Yes",-1))])]),i(n,{message:a(s).errors.hist_negmargins},null,8,["message"])]),t("div",null,[i(d,{for:"hist_stage"},{default:u(()=>e[51]||(e[51]=[m(" Histology Stage "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),y(t("select",{id:"hist_stage","onUpdate:modelValue":e[12]||(e[12]=l=>a(s).hist_stage=l),class:"mt-1 block w-full",required:""},e[52]||(e[52]=[t("option",{value:"",disabled:""},"Select Stage",-1),t("option",{value:"I"},"Stage I",-1),t("option",{value:"II"},"Stage II",-1),t("option",{value:"III"},"Stage III",-1),t("option",{value:"IV"},"Stage IV",-1)]),512),[[w,a(s).hist_stage]]),i(n,{message:a(s).errors.hist_stage},null,8,["message"])]),t("div",null,[i(d,{for:"extent_of_disease"},{default:u(()=>e[53]||(e[53]=[m(" Extent of Disease "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),y(t("select",{id:"extent_of_disease","onUpdate:modelValue":e[13]||(e[13]=l=>a(s).extent_of_disease=l),class:"mt-1 block w-full",required:""},[e[54]||(e[54]=t("option",{value:"",disabled:""},"Select Extent of Disease",-1)),(_(!0),p(k,null,h(T.value,(l,v)=>(_(),p("option",{key:v,value:l.extent_name},S(l.extent_name),9,Te))),128))],512),[[w,a(s).extent_of_disease]]),i(n,{class:"mt-2",message:a(s).errors.extent_of_disease},null,8,["message"])]),t("div",null,[i(d,{for:"tumor_size"},{default:u(()=>e[55]||(e[55]=[m(" Tumor Size "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"tumor_size",modelValue:a(s).tumor_size,"onUpdate:modelValue":e[14]||(e[14]=l=>a(s).tumor_size=l),type:"number",step:"0.01",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.tumor_size},null,8,["message"])]),t("div",null,[i(d,{for:"nodal_involvement"},{default:u(()=>e[56]||(e[56]=[m(" Nodal Involvement "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"nodal_involvement",modelValue:a(s).nodal_involvement,"onUpdate:modelValue":e[15]||(e[15]=l=>a(s).nodal_involvement=l),type:"number",step:"1",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.nodal_involvement},null,8,["message"])]),t("div",null,[i(d,{for:"metastatic_site"},{default:u(()=>e[57]||(e[57]=[m(" Metastatic Site "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),W.value?b("",!0):(_(),P(n,{key:0,message:"Please select one."})),t("div",ze,[(_(),p(k,null,h(oe,(l,v)=>t("label",{key:v,class:"inline-flex items-center"},[y(t("input",{type:"radio",name:"metastatic_site",value:l,"onUpdate:modelValue":e[16]||(e[16]=g=>a(s).metastatic_site=g),class:"form-radio",required:""},null,8,Ae),[[q,a(s).metastatic_site]]),e[58]||(e[58]=t("span",{class:"custom-radio"},null,-1)),t("span",Fe,S(l),1)])),64))]),i(n,{message:a(s).errors.metastatic_site},null,8,["message"])]),a(s).metastatic_site.includes("Other")?(_(),p("div",He,[i(d,{for:"other_metastatic_site"},{default:u(()=>e[59]||(e[59]=[m(" Specify Other Metastatic Site "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"other_metastatic_site",modelValue:a(s).other_metastatic_site,"onUpdate:modelValue":e[17]||(e[17]=l=>a(s).other_metastatic_site=l),type:"text",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.other_metastatic_site},null,8,["message"])])):b("",!0),t("div",null,[i(d,{for:"metastatic_notes",value:"Metastatic Notes"}),y(t("textarea",{id:"metastatic_notes","onUpdate:modelValue":e[18]||(e[18]=l=>a(s).metastatic_notes=l),class:"mt-1 block w-full"},null,512),[[fe,a(s).metastatic_notes]]),i(n,{message:a(s).errors.metastatic_notes},null,8,["message"])]),t("div",null,[i(d,{for:"multiple_primaries"},{default:u(()=>e[60]||(e[60]=[m(" Multiple Primaries "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),y(t("select",{id:"multiple_primaries","onUpdate:modelValue":e[19]||(e[19]=l=>a(s).multiple_primaries=l),class:"mt-1 block w-full",required:""},e[61]||(e[61]=[t("option",{value:"",disabled:""},"Select Multiple Primaries",-1),t("option",{value:"0"},"0",-1),t("option",{value:"1"},"1",-1),t("option",{value:"2"},"2",-1)]),512),[[w,a(s).multiple_primaries]]),i(n,{message:a(s).errors.multiple_primaries},null,8,["message"])]),a(s).multiple_primaries=="1"||a(s).multiple_primaries=="2"?(_(),p("div",$e,[i(d,{for:"other_primary_sites",value:"Other Primary Sites"}),t("div",Ge,[(_(!0),p(k,null,h(L.value.filter(l=>l.body_site_name!=="All"&&l.body_site_name!=="Negative"),(l,v)=>(_(),p("label",{key:v,class:"inline-flex items-center"},[y(t("input",{type:"checkbox",name:"other_primary_sites",value:l.body_site_name,"onUpdate:modelValue":e[20]||(e[20]=g=>a(s).other_primary_sites=g),class:"form-checkbox"},null,8,Re),[[B,a(s).other_primary_sites]]),t("span",je,S(l.body_site_name),1)]))),128))]),i(n,{message:a(s).errors.other_primary_sites},null,8,["message"]),a(s).other_primary_sites.includes("Others")?(_(),p("div",Ke,[i(d,{for:"additional_primary_site",value:"Specify Other Primary Site"}),i(f,{id:"additional_primary_site",modelValue:a(s).additional_primary_site,"onUpdate:modelValue":e[21]||(e[21]=l=>a(s).additional_primary_site=l),type:"text",class:"mt-1 block w-full"},null,8,["modelValue"]),i(n,{message:a(s).errors.additional_primary_site},null,8,["message"])])):b("",!0)])):b("",!0),t("div",null,[i(d,{for:"t_stage"},{default:u(()=>e[62]||(e[62]=[m(" T Stage "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"t_stage",modelValue:a(s).t_stage,"onUpdate:modelValue":e[22]||(e[22]=l=>a(s).t_stage=l),type:"number",min:"0",step:"1",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.t_stage},null,8,["message"])]),t("div",null,[i(d,{for:"n_stage"},{default:u(()=>e[63]||(e[63]=[m(" N Stage "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"n_stage",modelValue:a(s).n_stage,"onUpdate:modelValue":e[23]||(e[23]=l=>a(s).n_stage=l),type:"number",min:"0",step:"1",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.n_stage},null,8,["message"])]),t("div",null,[i(d,{for:"m_stage"},{default:u(()=>e[64]||(e[64]=[m(" M Stage "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"m_stage",modelValue:a(s).m_stage,"onUpdate:modelValue":e[24]||(e[24]=l=>a(s).m_stage=l),type:"number",min:"0",step:"1",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.m_stage},null,8,["message"])]),t("div",null,[i(d,{for:"g_stage"},{default:u(()=>e[65]||(e[65]=[m(" G Stage "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"g_stage",modelValue:a(s).g_stage,"onUpdate:modelValue":e[25]||(e[25]=l=>a(s).g_stage=l),type:"number",min:"0",step:"1",class:"mt-1 block w-full",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.g_stage},null,8,["message"])]),t("div",null,[i(d,{for:"grade"},{default:u(()=>e[66]||(e[66]=[m(" Disease Grade "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),y(t("select",{id:"grade","onUpdate:modelValue":e[26]||(e[26]=l=>a(s).grade=l),class:"mt-1 block w-full",required:""},e[67]||(e[67]=[t("option",{value:"",disabled:""},"Select Disease Grade",-1),t("option",{value:"0"},"0",-1),t("option",{value:"1"},"1",-1),t("option",{value:"2"},"2",-1),t("option",{value:"3"},"3",-1)]),512),[[w,a(s).grade]]),i(n,{message:a(s).errors.grade},null,8,["message"])]),t("div",null,[i(d,{for:"stage"},{default:u(()=>e[68]||(e[68]=[m(" Disease Stage "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),y(t("select",{id:"stage","onUpdate:modelValue":e[27]||(e[27]=l=>a(s).stage=l),class:"mt-1 block w-full",required:""},e[69]||(e[69]=[t("option",{value:"",disabled:""},"Select Stage",-1),t("option",{value:"I"},"Stage I",-1),t("option",{value:"II"},"Stage II",-1),t("option",{value:"III"},"Stage III",-1),t("option",{value:"IV"},"Stage IV",-1)]),512),[[w,a(s).stage]]),i(n,{message:a(s).errors.stage},null,8,["message"])]),t("div",null,[i(d,{for:"stage_type"},{default:u(()=>e[70]||(e[70]=[m(" Disease Stage Type "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),y(t("select",{id:"stage_type","onUpdate:modelValue":e[28]||(e[28]=l=>a(s).stage_type=l),class:"mt-1 block w-full",required:""},e[71]||(e[71]=[t("option",{value:"",disabled:""},"Select Stage Type",-1),t("option",{value:"clinical"},"Clinical",-1),t("option",{value:"pathologic"},"Pathologic",-1)]),512),[[w,a(s).stage_type]]),i(n,{class:"mt-2",message:a(s).errors.stage_type},null,8,["message"])]),t("div",null,[i(d,{for:"full_diagnosis"},{default:u(()=>e[72]||(e[72]=[m(" Full Diagnosis "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),i(f,{id:"full_diagnosis",modelValue:a(s).full_diagnosis,"onUpdate:modelValue":e[29]||(e[29]=l=>a(s).full_diagnosis=l),type:"text",class:"mt-1 block w-full",placeholder:"Enter Primary Site + Histology + Stage + Laterality",required:""},null,8,["modelValue"]),i(n,{message:a(s).errors.full_diagnosis},null,8,["message"])]),t("div",null,[i(d,{for:"disease_status"},{default:u(()=>e[73]||(e[73]=[m(" Disease Status "),t("span",{class:"red-asterisk"},"*",-1)])),_:1}),Y.value?b("",!0):(_(),P(n,{key:0,message:"Please select at least one."})),t("div",We,[(_(),p(k,null,h(re,(l,v)=>t("label",{key:v,class:"inline-flex items-center"},[y(t("input",{type:"checkbox",name:"disease_status",value:l,"onUpdate:modelValue":e[30]||(e[30]=g=>a(s).disease_status=g),class:"form-checkbox"},null,8,Ye),[[B,a(s).disease_status]]),t("span",Qe,S(l),1)])),64))]),i(n,{message:a(s).errors.disease_status},null,8,["message"])]),t("div",Ze,[i(J,{class:Z(["ms-4",{"opacity-25":a(s).processing}]),disabled:a(s).processing,onClick:e[31]||(e[31]=l=>M.value=1)},{default:u(()=>e[74]||(e[74]=[m(" Submit ")])),_:1},8,["class","disabled"]),i(J,{class:Z(["ms-4",{"opacity-25":a(s).processing}]),disabled:a(s).processing,onClick:e[32]||(e[32]=l=>M.value=2)},{default:u(()=>e[75]||(e[75]=[m(" Submit & Add Treatment History ")])),_:1},8,["class","disabled"])])],32),V.value.show?(_(),P(be,{key:0,message:V.value.message,type:V.value.type},null,8,["message","type"])):b("",!0)]),_:1})],64))}},ms=ke(Je,[["__scopeId","data-v-4b8319a3"]]);export{ms as default};
