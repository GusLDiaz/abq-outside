import {Component, OnInit} from "@angular/core";
import {Status} from "./shared/classes/status";
import {SessionService} from "./shared/services/session.service";
import {TrailService} from "./shared/services/trail.service";
import {Trail} from "./shared/classes/trail";

@Component({
	selector: "abq-outside",
	template: require("./app.component.html")
})
export class AppComponent implements OnInit{


	trails : Trail[];
	status : Status = null;

	 constructor(protected trailService: TrailService) {
	 // 	this.sessionService.setSession()
	 // 		.subscribe(status => this.status = status);
	  }
	  ngOnInit() : void {
	 	this.trailService.getAllTrails()
	 		.subscribe(trails => this.trails = trails);
	  }
}