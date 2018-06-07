import {Component} from "@angular/core";
import {Router} from "@angular/router";
import {CookieService} from "ng2-cookies";
//import {Status} from "../classes/status";

@Component({
	template: require("./navbar.component.html"),
	selector: "comment"
})

export class CommentComponent {

	//status: Status;

	constructor(
		private cookieService: CookieService,
		private router: Router
	) {}
}