import {Component} from "@angular/core"
import {TrailService} from "../shared/services/trail.service";

@Component ({
	template: require("./trail.component.html")
})
//
export class TrailComponent {
	constructor(protected trailService: TrailService) {
// 	}
// ngOnInit{
// 		trailUp()
// }
// 	trailUp() {
// 	this.TrailService.getAllPosts().subscribe(trails => this.posts = posts)
// 	}
	}
}