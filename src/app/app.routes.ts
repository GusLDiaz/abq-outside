import {RouterModule, Routes} from "@angular/router";


//import all needed components
import {HomeComponent} from "./home/home.component";
import {TrailComponent} from "./trail/trail.component";
import {NavBarComponent} from "./navbar/navbar.component";
import {ProfileComponent} from "./profile/profile.component";
//tentative Oauth-replaced
//import {SignInComponent} from "./shared/components/main-nav/signin.component";
//import {SignUpComponent} from "./sign-up/sign.up.component";
import {CommentComponent} from "./shared/comment/comment.component";
import {AboutComponent} from "./about/about.component";

// import all needed Services
import {CookieService} from "ng2-cookies";
import {JwtHelperService} from "@auth0/angular-jwt";
import {CommentService} from "./shared/services/comment.service";
import {ProfileService} from "./shared/services/profile.service";
import {TrailService} from "./shared/services/trail.service";
// import {SessionService} from "./shared/services/session.service";

//import all needed Interceptors
import {APP_BASE_HREF} from "@angular/common";
import {HTTP_INTERCEPTORS} from "@angular/common/http";
// import {AuthGuardService} from "./shared/guards/auth.guard";
// import {AuthService} from "./shared/services/auth.service";




// import {SignInService} from "./shared/services/sign.in.service";
// import {SignUpService} from "./shared/services/sign.up.service";


import {DeepDiveInterceptor} from "./shared/interceptors/deep.dive.interceptor";








export const allAppComponents : any[] = [HomeComponent, ProfileComponent, TrailComponent,CommentComponent,AboutComponent,NavbarComponent, MapComponent];

// //an array of routes that will be passed of to the module
 export const routes: Routes = [
	{path: "about", component: AboutComponent}
	{path: "trail/:trailId", component: TrailComponent},
	{path: "", component: HomeComponent}
];

// an array of services
const services : any[] = [AuthService, AuthGuardService, CookieService,JwtHelperService , ProfileService, SessionService,CommentService];

//an array of misc providers
 const providers : any[] = [
 	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	{provide: HTTP_INTERCEPTORS, multi: true}
//
];

export const appRoutingProviders: any[] = [providers];

export const routing = RouterModule.forRoot(routes);