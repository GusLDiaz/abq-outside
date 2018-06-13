import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Status} from "../classes/status";
import {Observable} from "rxjs/Observable";

@Injectable()
export class SessionService {

	constructor(protected http:HttpClient) {}

	private sessionUrl = "api/temp-auth/";

	setSession() {
		return (this.http.get<Status>(this.sessionUrl));
	}
	// getSessionApiProfile() {
	// 	return (this.http.get<>(this.sessionUrl));
	// }
}