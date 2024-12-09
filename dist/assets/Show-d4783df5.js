import{_ as c}from"./AppLayout-652b33cf.js";import l from"./DeleteUserForm-9652100b.js";import f from"./LogoutOtherBrowserSessionsForm-b53c26b4.js";import{S as s}from"./SectionBorder-9a4dccbf.js";import u from"./TwoFactorAuthenticationForm-772c3f6f.js";import d from"./UpdatePasswordForm-403823ab.js";import _ from"./UpdateProfileInformationForm-6ec7fd0b.js";import{o as e,c as h,w as p,a as i,e as r,b as t,f as a,F as g}from"./app-42536e63.js";import"./_plugin-vue_export-helper-c27b6911.js";import"./DropdownLink-ed97a0d6.js";import"./DialogModal-3bab71b1.js";import"./SectionTitle-70d6bf9f.js";import"./DangerButton-84c50f86.js";import"./InputError-d893a523.js";import"./SecondaryButton-a3709d4e.js";import"./TextInput-d9580142.js";import"./ActionMessage-e3c0bdd1.js";import"./PrimaryButton-cd95b478.js";import"./InputLabel-ff26f500.js";import"./FormSection-ad150bdf.js";const $={class:"max-w-7xl mx-auto py-10 sm:px-6 lg:px-8"},w={key:0},k={key:1},y={key:2},G={__name:"Show",props:{confirmsTwoFactorAuthentication:Boolean,sessions:Array},setup(m){return(o,n)=>(e(),h(c,{title:"Profile"},{header:p(()=>n[0]||(n[0]=[i("h2",{class:"font-semibold text-xl text-gray-800 leading-tight"}," Profile ",-1)])),default:p(()=>[i("div",null,[i("div",$,[o.$page.props.jetstream.canUpdateProfileInformation?(e(),r("div",w,[t(_,{user:o.$page.props.auth.user},null,8,["user"]),t(s)])):a("",!0),o.$page.props.jetstream.canUpdatePassword?(e(),r("div",k,[t(d,{class:"mt-10 sm:mt-0"}),t(s)])):a("",!0),o.$page.props.jetstream.canManageTwoFactorAuthentication?(e(),r("div",y,[t(u,{"requires-confirmation":m.confirmsTwoFactorAuthentication,class:"mt-10 sm:mt-0"},null,8,["requires-confirmation"]),t(s)])):a("",!0),t(f,{sessions:m.sessions,class:"mt-10 sm:mt-0"},null,8,["sessions"]),o.$page.props.jetstream.hasAccountDeletionFeatures?(e(),r(g,{key:3},[t(s),t(l,{class:"mt-10 sm:mt-0"})],64)):a("",!0)])])]),_:1}))}};export{G as default};