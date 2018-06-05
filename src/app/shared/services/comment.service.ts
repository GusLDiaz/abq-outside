import {Injectable} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {Status} from "../classes/status";
import {Comment} from "../classes/comment";
import {Observable} from "rxjs/Observable";

@Injectable()
export class CommentService {