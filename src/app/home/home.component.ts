import {Component, OnInit} from "@angular/core";
import {TrailService} from "../shared/services/trail.service";
import {Trail} from "../shared/classes/trail";

@Component({
	template: require("./home.component.html"),
	selector: "home"
})

export class HomeComponent implements OnInit{

	trails : Trail[] = [];
	constructor(protected trailService : TrailService){
	}
	ngOnInit() : void {
		this.trailService.getAllTrails()
			.subscribe(trails => this.trails = trails);
	}
}


