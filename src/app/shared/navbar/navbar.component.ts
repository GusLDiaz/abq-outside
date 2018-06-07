import {Component} from "@angular/core";
//import {CookieService} from "ng2-cookies";
import {Status} from "../classes/status";
import {Router} from "@angular/router";

@Component({
	template: require("./navbar.component.html"),
	//selector: "navbar"
})

export class NavBarComponent {

	status: Status;

	constructor(
		//private cookieService: CookieService,
		private router: Router
	) {}

	// signOut() : void {
	// 	this.signInService.getSignOut()
	//
	// 		.subscribe(status => {
	// 			this.status = status;
	// 			if(status.status === 200) {
	//
	// 				//delete cookies and jwt
	// 				this.cookieService.deleteAll();
	// 				localStorage.clear();
	//
	// 				//send user back home, refresh page
	// 				this.router.navigate([""]);
	// 				location.reload();
	// 				console.log("goodbye");
	// 			}
	// 		});
	// }
}