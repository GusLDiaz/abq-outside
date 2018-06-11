import {Component, OnInit} from "@angular/core";
import {TrailService} from "../shared/services/trail.service";
import {Trail} from "../shared/classes/trail";

@Component({
	template: require("./home.component.html"),
	selector: "home"
})

export class HomeComponent implements OnInit{
	trail : string = "";
	trails : Trail[] = [];
	trailTipName : string = "";
	constructor(protected trailService : TrailService){
	}
	ngOnInit() : void {
		this.trailService.getAllTrails()
			.subscribe(trails => this.trails = trails);
	}
	showSidePanel(trail: Trail) : void {

		this.trail = trail.trailId;
		this.trailTipName = trail.trailName;

	}
}
// var script = <HTMLScriptElement>document.getElementsByName("script")[0];
// <!-- Note: A custom script is used to activate tooltips:-->
//
// $(function(){
//   $('[data-toggle="tooltip"]').tooltip();
// });


