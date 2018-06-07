import {Component, OnInit} from "@angular/core"
import {TrailService} from "../shared/services/trail.service";
import {Trail} from "../shared/classes/trail";

@Component({
	template: require("./trail.component.html")
})
//
export class TrailComponent implements OnInit {
	trails : Trail[];
	constructor(protected trailService: TrailService) {
	}


	ngOnInit() {
		this.trailService.getAllTrails().subscribe(trails => this.trails = trails)
	}
}