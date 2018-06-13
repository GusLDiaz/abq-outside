import {RouterModule, Routes} from "@angular/router";
//import all needed components
import {HomeComponent} from "./home/home.component";
import {TrailComponent} from "./trail/trail.component";
import {NavBarComponent} from "./shared/navbar/navbar.component";
//import {ProfileComponent} from "./profile/profile.component";
import {AboutComponent} from "./about/about.component";
import {MapComponent} from "./map/map.component";
import {ProfileService} from "./shared/services/profile.service";
import {TrailService} from "./shared/services/trail.service";
import {SessionService} from "./shared/services/session.service";
//import all needed Interceptors
import {APP_BASE_HREF} from "@angular/common";
import {HTTP_INTERCEPTORS, HttpInterceptor} from "@angular/common/http";
import {DeepDiveInterceptor} from "./shared/interceptors/deep.dive.interceptor";
import {CommentService} from "./shared/services/comment.service";
import {CookieService} from "ng2-cookies";


//import {SessionService} from "./shared/services/session.service";

export const allAppComponents : any[] = [HomeComponent,TrailComponent,AboutComponent,NavBarComponent];

// //an array of routes that will be passed of to the module
 export const routes: Routes = [
	{path: "about", component: AboutComponent},
	{path: "trail/:trailId", component: TrailComponent},
	{path: "", component: HomeComponent}
];

// an array of services
const services : any[] = [ProfileService,TrailService,SessionService,CommentService,CookieService];

//an array of misc providers
 const providers : any[] = [
 	{provide: APP_BASE_HREF, useValue: window["_base_href"]},
	//{provide: HTTP_INTERCEPTORS, class: DeepDiveInterceptor, multi: true}
	{provide: HTTP_INTERCEPTORS, useClass : DeepDiveInterceptor, multi: true}
];
export const appRoutingProviders: any[] = [providers,services];

export const routing = RouterModule.forRoot(routes);