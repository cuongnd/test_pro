/*
 * DeLorean Ipsum v1.0.2
 * http://www.deloreanipsum.com/
 *
 * The Back To The Future script is copyright (c), Universal Studios & Bob Gale and used here as part of Fair Use.
 *
 * Copyright 2013, Polevaultweb
 * http://www.polevaultweb.com/
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */
(function ($) {
    $.fn.delorean = function (options) {
        $.fn.delorean.defaults = {
            type: 'paragraphs',
            amount: '3',
            tag: 'p',
            character: 'all',
            perPara: 5
        };
        var opts = $.extend({}, $.fn.delorean.defaults, options);
        var min_num = 0;
        var howmany = opts.amount;
        var delorean = new Array();

        delorean.push({
            "character": "Marty",
            "line": "Hey, Doc? Doc. Hello, anybody home? Einstein, come here, boy. What's going on? Wha- aw, god. Aw, Jesus. Whoa, rock and roll. Yo"
        });
        delorean.push({"character": "Doc", "line": "Marty, is that you?"});
        delorean.push({"character": "Marty", "line": "Hey, hey, Doc, where are you?"});
        delorean.push({
            "character": "Doc",
            "line": "Thank god I found you. Listen, can you meet me at Twin Pines Mall tonight at 1:15? I've made a major breakthrough, I'll need your assistance."
        });
        delorean.push({"character": "Marty", "line": "Wait a minute, wait a minute. 1:15 in the morning?"});
        delorean.push({"character": "Doc", "line": "Yeah."});
        delorean.push({"character": "Marty", "line": "What's going on? Where have you been all week?"});
        delorean.push({"character": "Doc", "line": "Working."});
        delorean.push({"character": "Marty", "line": "Where's Einstein, is he with you?"});
        delorean.push({"character": "Doc", "line": "Yeah, he's right here."});
        delorean.push({"character": "Marty", "line": "You know, Doc, you left your equipment on all week."});
        delorean.push({
            "character": "Doc",
            "line": "My equipment, that reminds me, Marty, you better not hook up to the amplifier. There's a slight possibility for overload."
        });
        delorean.push({"character": "Marty", "line": "Yeah, I'll keep that in mind."});
        delorean.push({
            "character": "Doc",
            "line": "Good, I'll see you tonight. Don't forget, now, 1:15 a.m., Twin Pines Mall."
        });
        delorean.push({"character": "Marty", "line": "Right."});
        delorean.push({"character": "Doc", "line": "Are those my clocks I hear?"});
        delorean.push({"character": "Marty", "line": "Yeah, it's 8:00."});
        delorean.push({
            "character": "Doc",
            "line": "They're late. My experiment worked. They're all exactly twenty-five minutes slow."
        });
        delorean.push({
            "character": "Marty",
            "line": "Wait a minute. Wait a minute, Doc. Are you telling me that it's 8:25?"
        });
        delorean.push({"character": "Doc", "line": "Precisely."});
        delorean.push({"character": "Marty", "line": "Damn. I'm late for school."});
        delorean.push({"character": "Marty", "line": "Hello, Jennifer."});
        delorean.push({
            "character": "Jennifer",
            "line": "Marty, don't go this way. Strickland's looking for you. If you're caught it'll be four tardies in a row."
        });
        delorean.push({"character": "Jennifer", "line": "Alright, c'mon, I think we're safe."});
        delorean.push({
            "character": "Marty",
            "line": "Y'know this time it wasn't my fault. The Doc set all of his clocks twenty-five minutes slow."
        });
        delorean.push({
            "character": "Strickland",
            "line": "Doc? Am I to understand you're still hanging around with Doctor Emmett Brown, McFly? Tardy slip for you, Miss Parker. And one for you McFly I believe that makes four in a row. Now let me give you a nickle's worth of advice, young man. This so called Doctor Brown is dangerous, he's a real nuttcase. You hang around with him you're gonna end up in big trouble."
        });
        delorean.push({"character": "Marty", "line": "Oh yes sir."});
        delorean.push({
            "character": "Strickland",
            "line": "You got a real attitude problem, McFly. You're a slacker. You remind me of you father when he went her, he was a slacker too."
        });
        delorean.push({"character": "Marty", "line": "Can I go now, Mr. Strickland?"});
        delorean.push({
            "character": "Strickland",
            "line": "I noticed you band is on the roster for dance auditions after school today. Why even bother Mcfly, you haven't got a chance, you're too much like your own man. No McFly ever amounted to anything in the history of Hill Valley."
        });
        delorean.push({"character": "Marty", "line": "Yeah, well history is gonna change."});
        delorean.push({"character": "Audition Judge", "line": "Next, please."});
        delorean.push({"character": "Marty", "line": "Alright, we're the pinheads."});
        delorean.push({
            "character": "Audition Judge",
            "line": "Okay, that's enough. Now stop the microphone. I'm sorry fellas. I'm afraid you're just too darn loud. Next, please. Where's the next group, please."
        });
        delorean.push({
            "character": "Election Van",
            "line": "Re-elect Mayor Goldie Wilson. Progress is his middle name."
        });
        delorean.push({
            "character": "Marty",
            "line": "I'm too loud. I can't believe it. I'm never gonna get a chance to play in front of anybody."
        });
        delorean.push({"character": "Jennifer", "line": "Marty, one rejection isn't the end of the world."});
        delorean.push({"character": "Marty", "line": "Nah, I just don't think I'm cut out for music."});
        delorean.push({
            "character": "Jennifer",
            "line": "But you're good, Marty, you're really good. And this audition tape of your is great, you gotta send it in to the record company. It's like Doc's always saying."
        });
        delorean.push({
            "character": "Marty",
            "line": "Yeah I know, If you put your mind to it you could accomplish anything."
        });
        delorean.push({"character": "Jennifer", "line": "That's good advice, Marty."});
        delorean.push({
            "character": "Marty",
            "line": "Alright, okay Jennifer. What if I send in the tape and they don't like it. I mean, what if they say I'm no good. What if they say, 'Get out of here, kid, you got no future.' I mean, I just don't think I can take that kind of rejection. Jesus, I'm beginning to sound like my old man."
        });
        delorean.push({
            "character": "Jennifer",
            "line": "C'mon, he's not that bad. At least he's letting you borrow the car tomorrow night."
        });
        delorean.push({
            "character": "Marty",
            "line": "Check out that four by four. That is hot. Someday, Jennifer, someday. Wouldn't it be great to take that truck up to the lake. Throw a couple of sleeping bags in the back. Lie out under the stars."
        });
        delorean.push({"character": "Jennifer", "line": "Stop it."});
        delorean.push({"character": "Marty", "line": "What?"});
        delorean.push({"character": "Jennifer", "line": "Does your mom know about tomorrow night?"});
        delorean.push({
            "character": "Marty",
            "line": "No, get out of town, my mom thinks I'm going camping with the guys. Well, Jennifer, my mother would freak out if she knew I was going up there with you. And I get this standard lecture about how she never did that kind of stuff when she was a kid. Now look, I think she was born a nun."
        });
        delorean.push({"character": "Jennifer", "line": "She's just trying to keep you respectable."});
        delorean.push({"character": "Marty", "line": "Well, she's not doing a very good job."});
        delorean.push({
            "character": "Woman",
            "line": "Save the clock tower, save the clock tower. Mayor Wilson is sponsoring an initiative to replace that clock. Thirty years ago, lightning struck that clock tower and the clock hasn't run since. We at the Hill Valley Preservation Society think it should be preserved exactly the way it is as part of our history and heritage."
        });
        delorean.push({"character": "Marty", "line": "Here you go, lady. There's a quarter."});
        delorean.push({"character": "Woman", "line": "Thank you, don't forget to take a flyer."});
        delorean.push({"character": "Marty", "line": "Right."});
        delorean.push({"character": "Woman", "line": "Save the clock tower."});
        delorean.push({"character": "Marty", "line": "Where were we."});
        delorean.push({"character": "Jennifer", "line": "Right about here."});
        delorean.push({"character": "Jennifer's Dad", "line": "Jennifer."});
        delorean.push({"character": "Jennifer", "line": "It's my dad."});
        delorean.push({"character": "Marty", "line": "Right."});
        delorean.push({"character": "Jennifer", "line": "I've gotta go."});
        delorean.push({"character": "Marty", "line": "I'll call you tonight."});
        delorean.push({
            "character": "Jennifer",
            "line": "I'll be at my grandma's. Here, let me give you the number. Bye."
        });
        delorean.push({"character": "Marty", "line": "Perfect, just perfect."});
        delorean.push({
            "character": "Biff",
            "line": "I can't believe you loaned me a car, without telling me it had a blindspot. I could've been killed."
        });
        delorean.push({
            "character": "George",
            "line": "Now, now, Biff, now, I never noticed any blindspot before when I would drive it. Hi, son."
        });
        delorean.push({
            "character": "Biff",
            "line": "But, what are you blind McFly, it's there. How else do you explain that wreck out there?"
        });
        delorean.push({
            "character": "George",
            "line": "Now, Biff, um, can I assume that your insurance is gonna pay for the damage?"
        });
        delorean.push({
            "character": "Biff",
            "line": "My insurance, it's your car, your insurance should pay for it. Hey, I wanna know who's gonna pay for this? I spilled beer all over it when that car smashed into me. Who's gonna pay my cleaning bill?"
        });
        delorean.push({"character": "George", "line": "Uh?"});
        delorean.push({"character": "Biff", "line": "And where's my reports?"});
        delorean.push({
            "character": "George",
            "line": "Uh, well, I haven't finished those up yet, but you know I figured since they weren't due till-"
        });
        delorean.push({
            "character": "Biff",
            "line": "Hello, hello, anybody home? Think, McFly, think. I gotta have time to get them re-typed. Do you realize what would happen if I hand in my reports in your handwriting. I'll get fired. You wouldn't want that to happen would you? Would you?"
        });
        delorean.push({
            "character": "George",
            "line": "Of course not, Biff, now I wouldn't want that to happen. Now, uh, I'll finish those reports up tonight, and I'll run em them on over first thing tomorrow, alright?"
        });
        delorean.push({
            "character": "Biff",
            "line": "Hey, not too early I sleep in on Saturday. Oh, McFly, your shoe's untied. Don't be so gullible, McFly. You got the place fixed up nice, McFly. I have you're car towed all the way to your house and all you've got for me is light beer. What are you looking at, butthead. Say hi to your mom for me."
        });
        delorean.push({
            "character": "George",
            "line": "I know what you're gonna say, son, and you're right, you're right, But Biff just happens to be my supervisor, and I'm afraid I'm not very good at confrontations."
        });
        delorean.push({
            "character": "Marty",
            "line": "The car, Dad, I mean He wrecked it, totaled it. I needed that car tomorrow night, Dad, I mean do you have any idea how important this was, do you have any clue?"
        });
        delorean.push({"character": "George", "line": "I know, and all I could say is I'm sorry."});
        delorean.push({
            "character": "George",
            "line": "Believe me, Marty, you're better off not having to worry about all the aggravation and headaches of playing at that dance."
        });
        delorean.push({
            "character": "David",
            "line": "He's absolutely right, Marty. the last thing you need is headaches."
        });
        delorean.push({
            "character": "Lorraine",
            "line": "Kids, we're gonna have to eat this cake by ourselves, Uncle Joey didn't make parole again. I think it would be nice, if you all dropped him a line."
        });
        delorean.push({"character": "Marty", "line": "Uncle Jailbird Joey?"});
        delorean.push({"character": "David", "line": "He's your brother, Mom."});
        delorean.push({
            "character": "Linda",
            "line": "Yeah, I think it's a major embarrassment having an uncle in prison."
        });
        delorean.push({"character": "Lorraine", "line": "We all make mistakes in life, children"});
        delorean.push({"character": "David", "line": "God dammit, I'm late."});
        delorean.push({
            "character": "Lorraine",
            "line": "David, watch your mouth. You come here and kiss your mother before you go, come here."
        });
        delorean.push({
            "character": "David",
            "line": "C'mon, Mom, make it fast, I'll miss my bus. Hey see you tonight, Pop. Woo, time to change that oil."
        });
        delorean.push({
            "character": "Linda",
            "line": "Hey Marty, I'm not your answering service, but you're outside pouting about the car, Jennifer Parker called you twice."
        });
        delorean.push({
            "character": "Lorraine",
            "line": "I don't like her, Marty. Any girl who calls a boy is just asking for trouble."
        });
        delorean.push({"character": "Linda", "line": "Oh Mom, there's nothing wrong with calling a boy."});
        delorean.push({
            "character": "Lorraine",
            "line": "I think it's terrible. Girls chasing boys. When I was your age I never chased a boy, or called a boy, or sat in a parked car with a boy."
        });
        delorean.push({"character": "Linda", "line": "Then how am I supposed to ever meet anybody."});
        delorean.push({"character": "Lorraine", "line": "Well, it will just happen. Like the way I met your father."});
        delorean.push({"character": "Linda", "line": "That was so stupid, Grandpa hit him with the car."});
        delorean.push({
            "character": "Lorraine",
            "line": "It was meant to be. Anyway, if Grandpa hadn't hit him, then none of you would have been born."
        });
        delorean.push({
            "character": "Linda",
            "line": "Yeah, well, I still don't understand what Dad was doing in the middle of the street."
        });
        delorean.push({"character": "Lorraine", "line": "What was it, George, bird watching?"});
        delorean.push({"character": "George", "line": "What Lorraine, what?"});
        delorean.push({
            "character": "Lorraine",
            "line": "Anyway, Grandpa hit him with the car and brought him into the house. He seemed so helpless, like a little lost puppy, my heart just went out for him."
        });
        delorean.push({
            "character": "Linda",
            "line": "Yeah Mom, we know, you've told us this story a million times. You felt sorry for him so you decided to go with him to The Fish Under The Sea Dance."
        });
        delorean.push({
            "character": "Lorraine",
            "line": "No, it was The Enchantment Under The Sea Dance. Our first date. It was the night of that terrible thunderstorm, remember George? Your father kissed me for the very first time on that dance floor. It was then I realized I was going to spend the rest of my life with him."
        });
        delorean.push({"character": "Marty", "line": "Hello."});
        delorean.push({"character": "Doc", "line": "Marty, you didn't fall asleep, did you?"});
        delorean.push({"character": "Marty", "line": "Uh Doc, uh no. No, don't be silly."});
        delorean.push({
            "character": "Doc",
            "line": "Listen, this is very important, I forgot my video camera, could you stop by my place and pick it up on your way to the mall?"
        });
        delorean.push({"character": "Marty", "line": "Um, yeah, I'm on my way."});
        delorean.push({"character": "Marty", "line": "Einstein, hey Einstein, where's the Doc, boy, huh? Doc"});
        delorean.push({"character": "Doc", "line": "Marty, you made it."});
        delorean.push({"character": "Marty", "line": "Yeah."});
        delorean.push({
            "character": "Doc",
            "line": "Welcome to my latest experiment. It's the one I've been waiting for all my life."
        });
        delorean.push({"character": "Marty", "line": "Um, well it's a delorean, right?"});
        delorean.push({
            "character": "Doc",
            "line": "Bear with me, Marty, all of your questions will be answered. Roll tape, we'll proceed."
        });
        delorean.push({"character": "Marty", "line": "Doc, is that a de-"});
        delorean.push({"character": "Doc", "line": "Never mind that now, never mind that now."});
        delorean.push({"character": "Marty", "line": "Alright, I'm ready."});
        delorean.push({
            "character": "Doc",
            "line": "Good evening, I'm Doctor Emmett Brown. I'm standing on the parking lot of Twin Pines Mall. It's Saturday morning, October 26, 1985, 1:18 a.m. and this is temporal experiment number one. C'mon, Einy, hey hey boy, get in there, that a boy, in you go, get down, that's it."
        });
        delorean.push({"character": "Marty", "line": "Whoa, whoa, okay."});
        delorean.push({
            "character": "Doc",
            "line": "Please note that Einstein's clock is in complete synchronization with my control watch."
        });
        delorean.push({"character": "Marty", "line": "Right check, Doc."});
        delorean.push({"character": "Doc", "line": "Good. Have a good trip Einstein, watch your head."});
        delorean.push({"character": "Marty", "line": "You have this thing hooked up to the car?"});
        delorean.push({
            "character": "Doc",
            "line": "Watch this. Not me, the car, the car. My calculations are correct, when this baby hits eighty-eight miles per hour, your gonna see some serious shit. Watch this, watch this. Ha, what did I tell you, eighty-eight miles per hour. The temporal displacement occurred at exactly 1:20 a.m. and zero seconds."
        });
        delorean.push({
            "character": "Marty",
            "line": "Hot, Jesus Christ, Doc. Jesus Christ, Doc, you disintegrated Einstein."
        });
        delorean.push({
            "character": "Doc",
            "line": "Calm down, Marty, I didn't disintegrate anything. The molecular structure of Einstein and the car are completely intact."
        });
        delorean.push({"character": "Marty", "line": "Where the hell are they."});
        delorean.push({
            "character": "Doc",
            "line": "The appropriate question is, weren't the hell are they. Einstein has just become the world's first time traveler. I sent him into the future. One minute into the future to be exact. And at exactly 1:21 a.m. we should cat h up with him and the time machine."
        });
        delorean.push({
            "character": "Marty",
            "line": "Wait a minute, wait a minute, Doc, are you telling me that you built a time machine out of a delorean."
        });
        delorean.push({
            "character": "Doc",
            "line": "The way I see it, if you're gonna build a time machine into a car why not do it with some style. Besides, the stainless, steel construction made the flux dispersal- look out."
        });
        delorean.push({"character": "Marty", "line": "What, what is it hot?"});
        delorean.push({
            "character": "Doc",
            "line": "It's cold, damn cold. Ha, ha, ha, Einstein, you little devil. Einstein's clock is exactly one minute behind mine, it's still ticking."
        });
        delorean.push({"character": "Marty", "line": "He's alright."});
        delorean.push({
            "character": "Doc",
            "line": "He's fine, and he's completely unaware that anything happened. As far as he's concerned the trip was instantaneous. That's why Einstein's watch is exactly one minute behind mine. He skipped over that minute to instantly arrive at this moment in time. Come here, I'll show you how it works. First, you turn the time circuits on. This readout tell you where you're going, this one tells you where you are, this one tells you where you were. You imput the destination time on this keypad. Say, you wanna see the signing of the declaration of independence, or witness the birth or Christ. Here's a red-letter date in the history of science, November 5, 1955. Yes, of course, November 5, 1955."
        });
        delorean.push({"character": "Marty", "line": "What, I don't get what happened."});
        delorean.push({
            "character": "Doc",
            "line": "That was the day I invented time travel. I remember it vividly. I was standing on the edge of my toilet hanging a clock, the porces was wet, I slipped, hit my head on the edge of the sink. And when I came to I had a revelation, a picture, a picture in my head, a picture of this. This is what makes time travel possible. The flux capacitor."
        });
        delorean.push({"character": "Marty", "line": "The flux capacitor."});
        delorean.push({
            "character": "Doc",
            "line": "It's taken me almost thirty years and my entire family fortune to realize the vision of that day, my god has it been that long. Things have certainly changed around here. I remember when this was all farmland as far as the eye could see. Old man Peabody, owned all of this. He had this crazy idea about breeding pine trees."
        });
        delorean.push({
            "character": "Marty",
            "line": "This is uh, this is heavy duty, Doc, this is great. Uh, does it run on regular unleaded gasoline?"
        });
        delorean.push({
            "character": "Doc",
            "line": "Unfortunately no, it requires something with a little more kick, plutonium."
        });
        delorean.push({
            "character": "Marty",
            "line": "Uh, plutonium, wait a minute, are you telling me that this sucker's nuclear?"
        });
        delorean.push({
            "character": "Doc",
            "line": "Hey, hey, keep rolling, keep rolling there. No, no, no, no, this sucker's electrical. But I need a nuclear reaction to generate the one point twenty-one gigawatts of electricity that I need."
        });
        delorean.push({
            "character": "Marty",
            "line": "Doc, you don't just walk into a store and ask for plutonium. Did you rip this off?"
        });
        delorean.push({
            "character": "Doc",
            "line": "Of course, from a group of Libyan Nationalists. They wanted me to build them a bomb, so I took their plutonium and in turn gave them a shiny bomb case full of used pinball machine parts."
        });
        delorean.push({"character": "Marty", "line": "Jesus."});
        delorean.push({"character": "Doc", "line": "Let's get you into a radiation suit, we must prepare to reload."});
        delorean.push({
            "character": "Doc",
            "line": "Safe now, everything's let lined. Don't you lose those tapes now, we'll need a record. Wup, wup, I almost forgot my luggage. Who knows if they've got cotton underwear in the future. I'm allergic to all synthetics."
        });
        delorean.push({"character": "Marty", "line": "The future, it's where you're going?"});
        delorean.push({
            "character": "Doc",
            "line": "That's right, twenty five years into the future. I've always dreamed on seeing the future, looking beyond my years, seeing the progress of mankind. I'll also be able to see who wins the next twenty-five world series."
        });
        delorean.push({"character": "Marty", "line": "Uh, Doc."});
        delorean.push({"character": "Doc", "line": "Huh?"});
        delorean.push({"character": "Marty", "line": "Uh, look me up when you get there."});
        delorean.push({
            "character": "Doc",
            "line": "Indeed I will, roll em. I, Doctor Emmett Brown, am about to embark on an historic journey. What have I been thinking of, I almost forgot to bring some extra plutonium. How did I ever expect to get back, one pallet one trip I must be out of my mind. What is it Einy? Oh my god, they found me, I don't know how but they found me. Run for it, Marty."
        });
        delorean.push({"character": "Marty", "line": "Who, who?"});
        delorean.push({"character": "Doc", "line": "Who do you think, the Libyans."});
        delorean.push({"character": "Marty", "line": "Holy shit."});
        delorean.push({"character": "Doc", "line": "Unroll their fire."});
        delorean.push({"character": "Marty", "line": "Doc, wait. No, bastards."});
        delorean.push({"character": "Libyan", "line": "Go. Go."});
        delorean.push({
            "character": "Marty",
            "line": "C'mon, more, dammit. Jeez. Holy shit. Let's see if you bastards can do ninety."
        });
        delorean.push({"character": "Marty", "line": "Ahh. Ahh."});
        delorean.push({"character": "Mother", "line": "Pa, what is it? What is it, Pa?"});
        delorean.push({"character": "Father", "line": "Looks like a airplane, without wings."});
        delorean.push({"character": "Son", "line": "That ain't no airplane, look."});
        delorean.push({"character": "Mother & Father", "line": "Ahh."});
        delorean.push({"character": "Father", "line": "Children."});
        delorean.push({"character": "Marty", "line": "Listen, woh. Hello, uh excuse me. Sorry about your barn."});
        delorean.push({"character": "Son", "line": "It's already mutated into human form, shoot it."});
        delorean.push({
            "character": "Father",
            "line": "Take that you mutated son-of-a-bitch. My pine, why you. You space bastard, you killed a pine."
        });
        delorean.push({
            "character": "Marty",
            "line": "Alright, alright, okay McFly, get a grip on yourself. It's all a dream. Just a very intense dream. Woh, hey, listen, you gotta help me."
        });
        delorean.push({"character": "Passenger", "line": "Don't stop, Wilbert, drive."});
        delorean.push({"character": "Marty", "line": "Can't be. This is nuts. Aw, c'mon."});
        delorean.push({
            "character": "Election Van",
            "line": "Remember, fellas, the future is in your hands. If you believe in progress, re-elect Mayor Red Thomas, progress is his middle name. Mayor Red Thomas's progress platform means more jobs, better education, bigger civic improvements, and lower taxes. On election day, cast your vote for a proven leader, re-elect Mayor Red Thomas..."
        });
        delorean.push({"character": "Marty", "line": "this has gotta be a dream."});
        delorean.push({"character": "Lou", "line": "Hey kid, what you do, jump ship?"});
        delorean.push({"character": "Marty", "line": "What?"});
        delorean.push({"character": "Lou", "line": "What's with the life preserver?"});
        delorean.push({"character": "Marty", "line": "I just wanna use the phone."});
        delorean.push({"character": "Lou", "line": "Yeah, it's in the back."});
        delorean.push({
            "character": "Marty",
            "line": "Brown, Brown, Brown, Brown, Brown, great, you're alive. Do you know where 1640 Riverside-"
        });
        delorean.push({"character": "Lou", "line": "Are you gonna order something, kid?"});
        delorean.push({"character": "Marty", "line": "Yeah, gimme a Tab."});
        delorean.push({"character": "Lou", "line": "Tab? I can't give you a tab unless you order something."});
        delorean.push({"character": "Marty", "line": "Right, gimme a Pepsi free."});
        delorean.push({"character": "Lou", "line": "You wanna a Pepsi, pall, you're gonna pay for it."});
        delorean.push({"character": "Marty", "line": "Well just gimme something without any sugar in it, okay?"});
        delorean.push({"character": "Lou", "line": "Without any sugar."});
        delorean.push({"character": "Biff", "line": "Hey McFly, what do you think you're doing."});
        delorean.push({"character": "Marty", "line": "Biff."});
        delorean.push({"character": "Biff", "line": "Hey I'm talking to you, McFly, you Irish bug."});
        delorean.push({"character": "George", "line": "Oh hey, Biff, hey, guys, how are you doing?"});
        delorean.push({"character": "Biff", "line": "Yeah, you got my homework finished, McFly?"});
        delorean.push({
            "character": "George",
            "line": "Uh, well, actually, I figured since it wasn't due till Monday-"
        });
        delorean.push({
            "character": "Biff",
            "line": "Hello, hello, anybody home? Think, McFly, think. I gotta have time to recopy it. Do your realize what would happen if I hand in my homework in your handwriting? I'd get kicked out of school. You wouldn't want that to happen would you, would you?"
        });
        delorean.push({
            "character": "George",
            "line": "Now, of course not, Biff, now, I wouldn't want that to happen."
        });
        delorean.push({"character": "Biff", "line": "Uh, no, no, no, no. What are you looking at, butt-head?"});
        delorean.push({
            "character": "Skinhead",
            "line": "Hey Biff, check out this guy's life preserver, dork thinks he's gonna drown."
        });
        delorean.push({"character": "Biff", "line": "Yeah, well, how about my homework, McFly?"});
        delorean.push({
            "character": "George",
            "line": "Uh, well, okay Biff, uh, I'll finish that on up tonight and I'll bring it over first thing tomorrow morning."
        });
        delorean.push({
            "character": "Biff",
            "line": "Hey not too early I sleep in Sunday's, hey McFly, you're shoe's untied, don't be so gullible, McFly."
        });
        delorean.push({"character": "George", "line": "Okay."});
        delorean.push({"character": "Biff", "line": "I don't wanna see you in here again."});
        delorean.push({"character": "George", "line": "Yeah, alright, bye-bye. What?"});
        delorean.push({"character": "Marty", "line": "You're George McFly."});
        delorean.push({"character": "George", "line": "Yeah, who are you?"});
        delorean.push({"character": "Goldie", "line": "Say, why do you let those boys push you around like that?"});
        delorean.push({"character": "George", "line": "Well, they're bigger than me."});
        delorean.push({
            "character": "Goldie",
            "line": "Stand tall, boy, have some respect for yourself. Don't you know that if you let people walk all over you know, they'll be walking all over you for the rest of your life? Listen to me, do you think I'm gonna spend the rest of my life in this slop house?"
        });
        delorean.push({"character": "Lou", "line": "Watch it, Goldie."});
        delorean.push({
            "character": "Goldie",
            "line": "No sir, I'm gonna make something out of myself, I'm going to night school and one day I'm gonna be somebody."
        });
        delorean.push({"character": "Marty", "line": "That's right, he's gonna be mayor."});
        delorean.push({
            "character": "Goldie",
            "line": "Yeah, I'm- mayor. Now that's a good idea. I could run for mayor."
        });
        delorean.push({"character": "Lou", "line": "A colored mayor, that'll be the day."});
        delorean.push({
            "character": "Goldie",
            "line": "You wait and see, Mr. Caruthers, I will be mayor and I'll be the most powerful mayor in the history of Hill Valley, and I'm gonna clean up this town."
        });
        delorean.push({"character": "Lou", "line": "Good, you could start by sweeping the floor."});
        delorean.push({"character": "Goldie", "line": "Mayor Goldie Wilson, I like the sound of that."});
        delorean.push({"character": "Marty", "line": "Hey Dad, George, hey, you on the bike."});
        delorean.push({"character": "Marty", "line": "He's a peeping tom. Dad."});
        delorean.push({
            "character": "Sam",
            "line": "Hey wait, wait a minute, who are you? Stella, another one of these damn kids jumped in front of my car. Come on out here, help me take him in the house."
        });
        delorean.push({"character": "Marty", "line": "Mom, is that you?"});
        delorean.push({
            "character": "Lorraine",
            "line": "There, there, now, just relax. You've been asleep for almost nine hours now."
        });
        delorean.push({
            "character": "Marty",
            "line": "I had a horrible nightmare, dreamed I went back in time, it was terrible."
        });
        delorean.push({"character": "Lorraine", "line": "Well, safe and sound, now, n good old 1955."});
        delorean.push({"character": "Marty", "line": "1955? You're my ma- you're my ma."});
        delorean.push({"character": "Lorraine", "line": "My name's Lorraine, Lorraine Baines."});
        delorean.push({"character": "Marty", "line": "Yeah, but you're uh, you're so, you're so thin."});
        delorean.push({
            "character": "Lorraine",
            "line": "Just relax now Calvin, you've got a big bruise on you're head."
        });
        delorean.push({"character": "Marty", "line": "Ah, where're my pants?"});
        delorean.push({
            "character": "Lorraine",
            "line": "Over there, on my hope chest. I've never seen purple underwear before, Calvin."
        });
        delorean.push({"character": "Marty", "line": "Calvin, why do you keep calling me Calvin?"});
        delorean.push({
            "character": "Lorraine",
            "line": "Well that's your name, isn't it? Calvin Klein. it's written all over your underwear. Oh, I guess they call you Cal, huh?"
        });
        delorean.push({"character": "Marty", "line": "Actually, people call me Marty."});
        delorean.push({
            "character": "Lorraine",
            "line": "Oh, pleased to meet you, Calvin Marty Klein. Do you mind if I sit here?"
        });
        delorean.push({"character": "Marty", "line": "No, fine, no , good, fine, good."});
        delorean.push({"character": "Lorraine", "line": "That's a big bruise you have there."});
        delorean.push({"character": "Marty", "line": "Ah."});
        delorean.push({"character": "Stella", "line": "Lorraine, are you up there?"});
        delorean.push({"character": "Lorraine", "line": "My god, it's my mother. Put your pants back on."});
        delorean.push({"character": "Stella", "line": "So tell me, Marty, how long have you been in port?"});
        delorean.push({"character": "Marty", "line": "Excuse me."});
        delorean.push({
            "character": "Stella",
            "line": "Yeah, I guessed you're a sailor, aren't you, that's why you wear that life preserver."
        });
        delorean.push({"character": "Marty", "line": "Uh, coast guard."});
        delorean.push({
            "character": "Stella",
            "line": "Sam, here's the young man you hit with your car out there. He's alright, thank god."
        });
        delorean.push({
            "character": "Sam",
            "line": "What were you doing in the middle of the street, a kid your age."
        });
        delorean.push({
            "character": "Stella",
            "line": "Don't pay any attention to him, he's in one of his moods. Sam, quit fiddling with that thing, come in here to dinner. Now let's see, you already know Lorraine, this is Milton, this is Sally, that's Toby, and over there in the playpen is little baby Joey."
        });
        delorean.push({"character": "Marty", "line": "So you're my Uncle Joey. Better get used to these bars, kid."});
        delorean.push({
            "character": "Stella",
            "line": "yes, Joey just loves being in his playpen. he cries whenever we take him out so we just leave him in there all the time. Well Marty, I hope you like meatloaf."
        });
        delorean.push({"character": "Marty", "line": "Well, uh, listen, uh, I really-"});
        delorean.push({"character": "Lorraine", "line": "Sit here, Marty."});
        delorean.push({
            "character": "Stella",
            "line": "Sam, quit fiddling with that thing and come in here and eat your dinner."
        });
        delorean.push({
            "character": "Sam",
            "line": "Ho ho ho, look at it roll. Now we could watch Jackie Gleason while we eat."
        });
        delorean.push({
            "character": "Lorraine",
            "line": "Our first television set, Dad just picked it up today. Do you have a television?"
        });
        delorean.push({"character": "Marty", "line": "Well yeah, you know we have two of them."});
        delorean.push({"character": "Milton", "line": "Wow, you must be rich."});
        delorean.push({"character": "Stella", "line": "Oh honey, he's teasing you, nobody has two television sets."});
        delorean.push({
            "character": "Marty",
            "line": "Hey, hey, I've seen this one, I've seen this one. This is a classic, this is where Ralph dresses up as the man from space."
        });
        delorean.push({"character": "Milton", "line": "What do you mean you've seen this, it's brand new."});
        delorean.push({"character": "Marty", "line": "Yeah well, I saw it on a rerun."});
        delorean.push({"character": "Milton", "line": "What's a rerun?"});
        delorean.push({"character": "Marty", "line": "You'll find out."});
        delorean.push({"character": "Stella", "line": "You know Marty, you look so familiar, do I know your mother?"});
        delorean.push({"character": "Marty", "line": "Yeah, I think maybe you do."});
        delorean.push({
            "character": "Stella",
            "line": "Oh, then I wanna give her a call, I don't want her to worry about you."
        });
        delorean.push({"character": "Marty", "line": "You can't, uh, that is, uh, nobody's home."});
        delorean.push({"character": "Stella", "line": "Oh."});
        delorean.push({"character": "Marty", "line": "Yet."});
        delorean.push({"character": "Stella", "line": "Oh."});
        delorean.push({"character": "Marty", "line": "Uh listen, do you know where Riverside Drive is?"});
        delorean.push({"character": "Sam", "line": "It's uh, the other end of town, a block past Maple."});
        delorean.push({"character": "Marty", "line": "A block passed Maple, that's John F. Kennedy Drive."});
        delorean.push({"character": "Sam", "line": "Who the hell is John F. Kennedy?"});
        delorean.push({
            "character": "Lorraine",
            "line": "Mother, with Marty's parents out of town, don't you think he oughta spend the night, after all, Dad almost killed him with the car."
        });
        delorean.push({
            "character": "Stella",
            "line": "That's true, Marty, I think you should spend the night. I think you're our responsibility."
        });
        delorean.push({"character": "Marty", "line": "Well gee, I don't know."});
        delorean.push({"character": "Lorraine", "line": "And he could sleep in my room."});
        delorean.push({
            "character": "Marty",
            "line": "I gotta go, uh, I gotta go. Thanks very much, it was wonderful, you were all great. See you all later, much later."
        });
        delorean.push({"character": "Stella", "line": "He's a very strange young man."});
        delorean.push({
            "character": "Sam",
            "line": "he's an idiot, comes from upbringing, parents were probably idiots too. Lorraine, if you ever have a kid like that, I'll disown you."
        });
        delorean.push({"character": "Marty", "line": "Doc?"});
        delorean.push({"character": "Doc", "line": "Don't say a word."});
        delorean.push({"character": "Marty", "line": "Doc."});
        delorean.push({
            "character": "Doc",
            "line": "I don't wanna know your name. I don't wanna know anything anything about you."
        });
        delorean.push({"character": "Marty", "line": "Listen, Doc."});
        delorean.push({"character": "Doc", "line": "Quiet."});
        delorean.push({"character": "Marty", "line": "Doc, Doc, it's me, Marty."});
        delorean.push({"character": "Doc", "line": "Don't tell me anything."});
        delorean.push({"character": "Marty", "line": "Doc, you gotta help-"});
        delorean.push({
            "character": "Doc",
            "line": "Quiet, quiet. I'm gonna read your thoughts. Let's see now, you've come from a great distance?"
        });
        delorean.push({"character": "Marty", "line": "Yeah, exactly."});
        delorean.push({
            "character": "Doc",
            "line": "Don't tell me. Uh, you want me to buy a subscription to the Saturday Evening Post?"
        });
        delorean.push({"character": "Marty", "line": "No."});
        delorean.push({
            "character": "Doc",
            "line": "Not a word, not a word, not a word now. Quiet, uh, donations, you want me to make a donation to the coast guard youth auxiliary?"
        });
        delorean.push({
            "character": "Marty",
            "line": "Doc, I'm from the future. I came here in a time machine that you invented. Now, I need your help to get back to the year 1985."
        });
        delorean.push({
            "character": "Doc",
            "line": "My god, do you know what this means? It means that this damn thing doesn't work at all."
        });
        delorean.push({
            "character": "Marty",
            "line": "Doc, you gotta help me. you were the only one who knows how your time machine works."
        });
        delorean.push({"character": "Doc", "line": "Time machine, I haven't invented any time machine."});
        delorean.push({
            "character": "Marty",
            "line": "Okay, alright, I'll prove it to you. Look at my driver's license, expires 1987. Look at my birthday, for crying out load I haven't even been born yet. And, look at this picture, my brother, my sister, and me. Look at the sweatshirt, Doc, class of 1984."
        });
        delorean.push({
            "character": "Doc",
            "line": "Pretty Mediocre photographic fakery, they cut off your brother's hair."
        });
        delorean.push({"character": "Marty", "line": "I'm telling the truth, Doc, you gotta believe me."});
        delorean.push({
            "character": "Doc",
            "line": "So tell me, future boy, who's president of the United States in 1985?"
        });
        delorean.push({"character": "Marty", "line": "Ronald Reagon."});
        delorean.push({
            "character": "Doc",
            "line": "Ronald Reagon, the actor? Then who's vice president, Jerry Lewis? I suppose Jane Wymann is the first lady."
        });
        delorean.push({"character": "Marty", "line": "Whoa, wait, Doc."});
        delorean.push({"character": "Doc", "line": "And Jack Benny is secretary of the Treasury."});
        delorean.push({"character": "Marty", "line": "Look, you gotta listen to me."});
        delorean.push({
            "character": "Doc",
            "line": "I got enough practical jokes for one evening. Good night, future boy."
        });
        delorean.push({
            "character": "Marty",
            "line": "No wait, Doc, the bruise, the bruise on your head, I know how that happened, you told me the whole story. you were standing on your toilet and you were hanging a clock, and you fell, and you hit your head on the sink, and that's when you came up with the idea for the flux capacitor, which makes time travel possible."
        });
        delorean.push({"character": "Marty", "line": "Something wrong with the starter, so I hid it."});
        delorean.push({"character": "Doc", "line": "After I fell off my toilet, I drew this."});
        delorean.push({"character": "Marty", "line": "Flux capacitor."});
        delorean.push({
            "character": "Doc",
            "line": "It works, ha ha ha ha, it works. I finally invent something that works."
        });
        delorean.push({"character": "Marty", "line": "Bet your ass it works."});
        delorean.push({
            "character": "Doc",
            "line": "Well, now we gotta sneak this back into my laboratory, we've gotta get you home."
        });
        delorean.push({"character": "Marty", "line": "Okay Doc, this is it."});
        delorean.push({
            "character": "TV Doc",
            "line": "Never mind that, never mind that now, never mind that, never mind-"
        });
        delorean.push({"character": "Doc", "line": "Why that's me, look at me, I'm an old man."});
        delorean.push({
            "character": "TV Doc",
            "line": "Good evening, I'm Doctor Emmet Brown, I'm standing here on the parking lot of-"
        });
        delorean.push({
            "character": "Doc",
            "line": "Thank god I still got my hair. What on Earth is that thing I'm wearing?"
        });
        delorean.push({"character": "Marty", "line": "Well, this is a radiation suit."});
        delorean.push({
            "character": "Doc",
            "line": "Radiation suit, of course, cause all of the fall out from the atomic wars. This is truly amazing, a portable television studio. No wonder your president has to be an actor, he's gotta look good on television."
        });
        delorean.push({"character": "Marty", "line": "whoa, this is it, this is the part coming up, Doc."});
        delorean.push({
            "character": "TV Doc",
            "line": "No no no this sucker's electrical, but I need a nuclear reaction to generate the one point twenty-one gigawatts of electricity-"
        });
        delorean.push({"character": "Doc", "line": "What did I just say?"});
        delorean.push({
            "character": "TV Doc",
            "line": "No no no this sucker's electrical, but I need a nuclear reaction to generate the one point twenty-one gigawatts of electricity that I need."
        });
        delorean.push({
            "character": "Doc",
            "line": "One point twenty-one gigawatts. One point twenty-one gigawatts. Great Scott."
        });
        delorean.push({"character": "Marty", "line": "What the hell is a gigawatt?"});
        delorean.push({
            "character": "Doc",
            "line": "How could I have been so careless. One point twenty-one gigawatts. Tom, how am I gonna generate that kind of power, it can't be done, it can't."
        });
        delorean.push({"character": "Marty", "line": "Doc, look, all we need is a little plutonium."});
        delorean.push({
            "character": "Doc",
            "line": "I'm sure that in 1985, plutonium is available at every corner drug store, but in 1955, it's a little hard to come by. Marty, I'm sorry, but I'm afraid you're stuck here."
        });
        delorean.push({
            "character": "Marty",
            "line": "whoa, whoa Doc, stuck here, I can't be stuck here, I got a life in 1985. I got a girl."
        });
        delorean.push({"character": "Doc", "line": "Is she pretty?"});
        delorean.push({
            "character": "Marty",
            "line": "Doc, she's beautiful. She's crazy about me. Look at this, look what she wrote me, Doc. That says it all. Doc, you're my only hope."
        });
        delorean.push({
            "character": "Doc",
            "line": "Marty, I'm sorry, but the only power source capable of generating one point twenty-one gigawatts of electricity is a bolt of lightning."
        });
        delorean.push({"character": "Marty", "line": "What did you say?"});
        delorean.push({
            "character": "Doc",
            "line": "A bolt of lightning, unfortunately, you never know when or where it's ever gonna strike."
        });
        delorean.push({"character": "Marty", "line": "We do now."});
        delorean.push({
            "character": "Doc",
            "line": "This is it. This is the answer. It says here that a bolt of lightning is gonna strike the clock tower precisely at 10:04 p.m. next Saturday night. If we could somehow harness this bolt of lightning, channel it into the flux capacitor, it just might work. Next Saturday night, we're sending you back to the future."
        });
        delorean.push({
            "character": "Marty",
            "line": "Okay, alright, Saturday is good, Saturday's good, I could spend a week in 1955. I could hang out, you could show me around."
        });
        delorean.push({
            "character": "Doc",
            "line": "Marty, that's completely out of the question, you must not leave this house. you must not see anybody or talk to anybody. Anything you do could have serious reprocautions on future events. Do you understand?"
        });
        delorean.push({"character": "Marty", "line": "Yeah, sure, okay."});
        delorean.push({"character": "Doc", "line": "Marty, you interacted with anybody else today, besides me?"});
        delorean.push({"character": "Marty", "line": "Um, yeah well I might have sort of ran into my parents."});
        delorean.push({
            "character": "Doc",
            "line": "Great Scott. Let me see that photograph again of your brother. Just as I thought, this proves my theory, look at your brother."
        });
        delorean.push({"character": "Marty", "line": "His head's gone, it's like it's been erased."});
        delorean.push({"character": "Doc", "line": "Erased from existence."});
        delorean.push({"character": "Marty", "line": "Whoa, they really cleaned this place up, looks brand new."});
        delorean.push({
            "character": "Doc",
            "line": "Now remember, according to my theory you interfered with with your parent's first meeting. They don't meet, they don't fall in love, they won't get married and they wont have kids. That's why your older brother's disappeared from that photograph. Your sister will follow and unless you repair the damages, you will be next."
        });
        delorean.push({"character": "Marty", "line": "This sounds pretty heavy."});
        delorean.push({"character": "Doc", "line": "Weight has nothing to do with it."});
        delorean.push({"character": "Doc", "line": "Which one's your pop?"});
        delorean.push({"character": "Marty", "line": "That's him."});
        delorean.push({
            "character": "George",
            "line": "Okay, okay you guys, oh ha ha ha very funny. Hey you guys are being real mature."
        });
        delorean.push({"character": "Doc", "line": "Maybe you were adopted."});
        delorean.push({
            "character": "George",
            "line": "Okay, real mature guys. Okay, Biff, will you pick up my books?"
        });
        delorean.push({"character": "Strickland", "line": "McFly."});
        delorean.push({"character": "Marty", "line": "That's Strickland. Jesus, didn't that guy ever have hair?"});
        delorean.push({
            "character": "Strickland",
            "line": "Shape up, man. You're a slacker. You wanna be a slacker for the rest of your life?"
        });
        delorean.push({"character": "George", "line": "No."});
        delorean.push({"character": "Doc", "line": "What did your mother ever see in that kid?"});
        delorean.push({
            "character": "Marty",
            "line": "I don't know, Doc, I guess she felt sorry for him cause her did hit him with the car, hit me with the car."
        });
        delorean.push({
            "character": "Doc",
            "line": "That's a Florence Nightingale effect. It happens in hospitals when nurses fall in love with their patients. Go to it, kid."
        });
        delorean.push({
            "character": "Marty",
            "line": "Hey George, buddy, hey, I've been looking all over for you. You remember me, the guy who saved your life the other day."
        });
        delorean.push({"character": "George", "line": "Yeah."});
        delorean.push({"character": "Marty", "line": "Good, there's somebody I'd like you to meet. Lorraine."});
        delorean.push({"character": "Lorraine", "line": "Calvin."});
        delorean.push({"character": "Marty", "line": "I'd like you to meet my good friend George McFly."});
        delorean.push({"character": "George", "line": "Hi, it's really a pleasure to meet you."});
        delorean.push({"character": "Lorraine", "line": "How's your head?"});
        delorean.push({"character": "Marty", "line": "Well uh, good, fine."});
        delorean.push({
            "character": "Lorraine",
            "line": "Oh, I've been so worried about you ever since you ran off the other night. Are you okay? I'm sorry I have to go. Isn't he a dream boat?"
        });
        delorean.push({"character": "Marty", "line": "Doc, she didn't even look at him."});
        delorean.push({
            "character": "Doc",
            "line": "This is more serious than I thought. Apparently your mother is amorously infatuated with you instead of your father."
        });
        delorean.push({
            "character": "Marty",
            "line": "Whoa, wait a minute, Doc, are you telling me that my mother has got the hots for me?"
        });
        delorean.push({"character": "Doc", "line": "Precisely."});
        delorean.push({"character": "Marty", "line": "Whoa, this is heavy."});
        delorean.push({
            "character": "Doc",
            "line": "There's that word again, heavy. Why are things so heavy in the future. Is there a problem with the Earth's gravitational pull?"
        });
        delorean.push({"character": "Marty", "line": "What?"});
        delorean.push({
            "character": "Doc",
            "line": "The only way we're gonna get those two to successfully meet is if they're alone together. So you've got to get your father and mother to interact at some sort of social-"
        });
        delorean.push({"character": "Marty", "line": "What, well you mean like a date?"});
        delorean.push({"character": "Doc", "line": "Right."});
        delorean.push({
            "character": "Marty",
            "line": "What kind of date? I don't know, what do kids do in the fifties?"
        });
        delorean.push({
            "character": "Doc",
            "line": "Well, they're your parents, you must know them. What are their common interests, what do they like to do together?"
        });
        delorean.push({"character": "Marty", "line": "Nothing."});
        delorean.push({"character": "Doc", "line": "Look, there's a rhythmic ceremonial ritual coming up."});
        delorean.push({
            "character": "Marty",
            "line": "Of course, the Enchantment Under The Sea Dance they're supposed to go to this, that's where they kiss for the first time."
        });
        delorean.push({
            "character": "Doc",
            "line": "Alright kid, you stick to your father like glue and make sure that he takes her to the dance."
        });
        delorean.push({
            "character": "Marty",
            "line": "George, buddy. remember that girl I introduced you to, Lorraine. What are you writing?"
        });
        delorean.push({
            "character": "George",
            "line": "Uh, stories, science fiction stories, about visitors coming down to Earth from another planet."
        });
        delorean.push({
            "character": "Marty",
            "line": "Get out of town, I didn't know you did anything creative. Ah, let me read some."
        });
        delorean.push({
            "character": "George",
            "line": "Oh, no no no, I never uh, I never let anybody read my stories."
        });
        delorean.push({"character": "Marty", "line": "Why not?"});
        delorean.push({
            "character": "George",
            "line": "Well, what if they didn't like them, what if they told me I was no good. I guess that would be pretty hard for somebody to understand."
        });
        delorean.push({
            "character": "Marty",
            "line": "Uh no, not hard at all. So anyway, George, now Lorraine, she really likes you. She told me to tell you that she wants you to ask her to the Enchantment Under The Sea Dance."
        });
        delorean.push({"character": "George", "line": "Really."});
        delorean.push({"character": "Marty", "line": "oh yeah, all you gotta do is go over there and ask her."});
        delorean.push({
            "character": "George",
            "line": "What, right here right now in the cafeteria? What is she said no? I don't know if I could take that kind of rejection. Besides, I think she'd rather go with somebody else."
        });
        delorean.push({"character": "Marty", "line": "Who?"});
        delorean.push({"character": "George", "line": "Biff."});
        delorean.push({"character": "Biff", "line": "C'mon, c'mon."});
        delorean.push({"character": "Lorraine", "line": "Leave me alone."});
        delorean.push({
            "character": "Biff",
            "line": "You want it, you know you want it, and you know you want me to give it to you."
        });
        delorean.push({"character": "Lorraine", "line": "Shut your filthy mouth, I'm not that kind of girl."});
        delorean.push({"character": "Biff", "line": "Well maybe you are and you just don't know it yet."});
        delorean.push({"character": "Lorraine", "line": "Get your meat hooks off of me."});
        delorean.push({"character": "Marty", "line": "You heard her she said get your meat hooks, off, uh please."});
        delorean.push({
            "character": "Biff",
            "line": "So what's it to you, butthead. You know you've been looking for a, since you're new here, I'm gonna cut you a break, today. So why don't you make like a tree, and get out of here."
        });
        delorean.push({"character": "Marty", "line": "George."});
        delorean.push({"character": "George", "line": "Why do you keep following me around?"});
        delorean.push({
            "character": "Marty",
            "line": "Look, George, I'm telling you George, if you do not ask Lorraine to that dance, I'm gonna regret it for the rest of my life."
        });
        delorean.push({
            "character": "George",
            "line": "But I can't go to the dance, I'll miss my favorite television program, Science Fiction Theater."
        });
        delorean.push({
            "character": "Marty",
            "line": "Yeah but George, Lorraine wants to go with you. Give her a break."
        });
        delorean.push({
            "character": "George",
            "line": "Look, I'm just not ready to ask Lorraine out to the dance, and not you, nor anybody else on this planet is gonna make me change my mind."
        });
        delorean.push({"character": "Marty", "line": "Science Fiction Theater."});
        delorean.push({"character": "George", "line": "Who are you?"});
        delorean.push({
            "character": "Marty",
            "line": "Silence Earthling. my name is Darth Vader. I'm am an extra-terrestrial from the planet Vulcan."
        });
        delorean.push({"character": "George", "line": "Marty. Marty. Marty."});
        delorean.push({
            "character": "Marty",
            "line": "Hey, George, buddy, you weren't at school, what have you been doing all day?"
        });
        delorean.push({
            "character": "George",
            "line": "I over slept, look I need your help. I have to ask Lorraine out but I don't know how to do it. I have to ask Lorraine out but I don't know how to do it."
        });
        delorean.push({
            "character": "Marty",
            "line": "Alright, okay listen, keep your pants on, she's over in the cafe. God, how do you do this? What made you change your mind, George?"
        });
        delorean.push({
            "character": "George",
            "line": "Last night, Darth Vader came down from planet Vulcan. And he told me that if I didn't take Lorraine, that he'd melt my brain."
        });
        delorean.push({
            "character": "Marty",
            "line": "Yeah, well uh, lets keep this brain melting stuff to ourselves, okay?"
        });
        delorean.push({"character": "George", "line": "Oh, yeah, yeah."});
        delorean.push({
            "character": "Marty",
            "line": "Alright, okay. Alright, there she is, George. Just go in there and invite her."
        });
        delorean.push({"character": "George", "line": "Okay, but I don't know what to say."});
        delorean.push({
            "character": "Marty",
            "line": "Just say anything, George, say what ever's natural, the first thing that comes to your mind."
        });
        delorean.push({"character": "George", "line": "Nothing's coming to my mind."});
        delorean.push({"character": "Marty", "line": "Jesus, George, it's a wonder I was ever born."});
        delorean.push({"character": "George", "line": "What, what?"});
        delorean.push({
            "character": "Marty",
            "line": "Nothing, nothing, nothing, look tell her destiny has brought you together, tell her that she's the most beautiful you have ever seen. Girls like that stuff. What, what are you doing George?"
        });
        delorean.push({"character": "George", "line": "I'm writing this down, this is good stuff."});
        delorean.push({"character": "Marty", "line": "Yeah okay."});
        delorean.push({"character": "George", "line": "Oh."});
        delorean.push({"character": "Marty", "line": "Let's go."});
        delorean.push({"character": "George", "line": "Oh."});
        delorean.push({"character": "Marty", "line": "Will you take care of that?"});
        delorean.push({
            "character": "George",
            "line": "Right. Lou, gimme a milk, chocolate. Lorraine, my density has popped me to you."
        });
        delorean.push({"character": "Lorraine", "line": "What?"});
        delorean.push({"character": "George", "line": "Oh, what I meant to day was-"});
        delorean.push({"character": "Lorraine", "line": "Hey, don't I know you from somewhere?"});
        delorean.push({
            "character": "George",
            "line": "Yes, yes, I'm George, George McFly, and I'm your density. I mean, I'm your destiny."
        });
        delorean.push({"character": "Lorraine", "line": "Oh."});
        delorean.push({
            "character": "Biff",
            "line": "Hey, McFly, I thought I told you never to come in here. Well it's gonna cost you. How much money you got on you?"
        });
        delorean.push({"character": "George", "line": "Well, Biff."});
        delorean.push({"character": "Biff", "line": "Alright, punk, now-"});
        delorean.push({"character": "Marty", "line": "Whoa, whoa, Biff, what's that?"});
        delorean.push({"character": "Lorraine", "line": "That's Calvin Klein, oh my god, he's a dream."});
        delorean.push({"character": "Marty", "line": "Whoa, whoa, kid, kid, stop, stop, stop, stop."});
        delorean.push({"character": "Kid", "line": "Hey."});
        delorean.push({"character": "Marty", "line": "I'll get it back to you, alright?"});
        delorean.push({"character": "Kid", "line": "You broke it. Wow, look at him go."});
        delorean.push({"character": "Biff", "line": "Let's get him."});
        delorean.push({"character": "Girl", "line": "What's that thing he's on?"});
        delorean.push({"character": "Boy", "line": "It's a board with wheels."});
        delorean.push({"character": "Lorraine", "line": "He's an absolute dream."});
        delorean.push({"character": "Marty", "line": "Ah. Whoa."});
        delorean.push({"character": "Biff", "line": "I'm gonna ram him."});
        delorean.push({"character": "Biff, Matches, 3-D, & Skinhead", "line": "Shit."});
        delorean.push({"character": "Marty", "line": "Thanks a lot, kid."});
        delorean.push({"character": "Biff", "line": "I'm gonna get that son-of-a-bitch."});
        delorean.push({"character": "Girlfriend #1", "line": "Where does he come from?"});
        delorean.push({"character": "Girlfriend #2", "line": "Yeah, where does he live?"});
        delorean.push({"character": "Lorraine", "line": "I don't know, but I'm gonna find out."});
        delorean.push({
            "character": "Doc",
            "line": "My god, they found me. I don't know how but they found me. Run for it, Marty. My god, they found me. I don't know how but they found me. Run for it, Marty."
        });
        delorean.push({"character": "Marty", "line": "Doc."});
        delorean.push({
            "character": "Doc",
            "line": "Oh, hi , Marty. I didn't hear you come in. Fascinating device, this video unit."
        });
        delorean.push({
            "character": "Marty",
            "line": "Listen, Doc, you know there's something I haven't told you about the night we made that tape."
        });
        delorean.push({
            "character": "Doc",
            "line": "Please, Marty, don't tell me, no man should know too much about their own destiny."
        });
        delorean.push({"character": "Marty", "line": "You don't understand."});
        delorean.push({
            "character": "Doc",
            "line": "I do understand. If I know too much about my own future I could endanger my own existence, just as you endangered yours."
        });
        delorean.push({"character": "Marty", "line": "Your, your right."});
        delorean.push({
            "character": "Doc",
            "line": "Let me show you my plan for sending you home. Please excuse the crudity of this model, I didn't have time to build it to scale or to paint it."
        });
        delorean.push({"character": "Marty", "line": "Its good."});
        delorean.push({
            "character": "Doc",
            "line": "Oh, thank you, thank you. Okay now, we run some industrial strength electrical cable from the top of the clocktower down to spreading it over the street between two lamp posts. Meanwhile, we out-fitted the vehicle with this big pole and hook which runs directly into the flux-capacitor. At the calculated moment, you start off from down the street driving toward the cable execrating to eighty-eight miles per hour. According to the flyer, at !0:04 pm lightning will strike the clocktower sending one point twenty-one gigawatts into the flux-capacitor, sending you back to 1985. Alright now, watch this. You wind up the car and release it, I'll simulate the lightening. Ready, set, release. Huhh."
        });
        delorean.push({"character": "Marty", "line": "You extol me with a lot of confidence, Doc."});
        delorean.push({
            "character": "Doc",
            "line": "Don't worry, I'll take care of the lightning, you take care of your pop. By the way, what happened today, did he ask her out?"
        });
        delorean.push({"character": "Marty", "line": "Uh, I think so."});
        delorean.push({
            "character": "Doc",
            "line": "What did she say? It's your mom, she's tracked you down. Quick, let's cover the time machine."
        });
        delorean.push({"character": "Lorraine", "line": "Hi, Marty."});
        delorean.push({"character": "Marty", "line": "Uh, Lorraine. How did you know I was here?"});
        delorean.push({"character": "Lorraine", "line": "I followed you."});
        delorean.push({"character": "Marty", "line": "Oh, uh, this is my Doc, Uncle, Brown."});
        delorean.push({"character": "Lorraine", "line": "Hi."});
        delorean.push({"character": "Marty", "line": "Hello."});
        delorean.push({
            "character": "Lorraine",
            "line": "Marty, this may seem a little foreward, but I was wondering if you would ask me to the Enchantment Under The Sea Dance on Saturday."
        });
        delorean.push({"character": "Marty", "line": "Uh, you mean nobody's asked you?"});
        delorean.push({"character": "Lorraine", "line": "No, not yet."});
        delorean.push({"character": "Marty", "line": "What about George?"});
        delorean.push({
            "character": "Lorraine",
            "line": "George McFly? Oh, he's kinda cute and all, but, well, I think a man should be strong, so he could stand up for himself, and protect the woman he loves. Don't you?"
        });
        delorean.push({"character": "Marty", "line": "Yeah."});
        delorean.push({
            "character": "George",
            "line": "I still don't understand, how am I supposed to go to the dance with her, if she's already going to the dance with you."
        });
        delorean.push({
            "character": "Marty",
            "line": "Cause, George, she wants to go to the dance with you, she just doesn't know it yet. That's why we got to show her that you, George McFly, are a fighter. You're somebody who's gonna stand up for yourself, someone who's gonna protect her."
        });
        delorean.push({"character": "George", "line": "Yeah, but I never picked a fight in my entire life."});
        delorean.push({
            "character": "Marty",
            "line": "Your not gonna be picking a fight, Dad, dad dad daddy-o. You're coming to a rescue, right? Okay, let's go over the plan again. 8:55, where are you gonna be."
        });
        delorean.push({"character": "George", "line": "I'm gonna be at the dance."});
        delorean.push({"character": "Marty", "line": "Right, and where am I gonna be?"});
        delorean.push({"character": "George", "line": "You're gonna be in the car with her."});
        delorean.push({
            "character": "Marty",
            "line": "Right, okay, so right around 9:00 she's gonna get very angry with me."
        });
        delorean.push({"character": "George", "line": "Why is she gonna get angry with you?"});
        delorean.push({
            "character": "Marty",
            "line": "Well, because George, nice girls get angry when guys take advantage of them."
        });
        delorean.push({"character": "George", "line": "Ho, you mean you're gonna touch her on her-"});
        delorean.push({
            "character": "Marty",
            "line": "No, no, George, look, it's just an act, right? Okay, so 9:00 you're strolling through the parking lot, you see us struggling in the car, you walk up, you open the door and you say, your line, George."
        });
        delorean.push({
            "character": "George",
            "line": "Oh, uh, hey you, get your damn hands off her. Do you really think I oughta swear?"
        });
        delorean.push({
            "character": "Marty",
            "line": "Yes, definitely, god-dammit George, swear. Okay, so now, you come up, you punch me in the stomach, I'm out for the count, right? And you and Lorraine live happily ever after."
        });
        delorean.push({
            "character": "George",
            "line": "Oh, you make it sound so easy. I just, I wish I wasn't so scared."
        });
        delorean.push({
            "character": "Marty",
            "line": "George, there's nothing to be scared of. All it takes is a little self confidence. You know, if you put your mind to it, you could accomplish anything."
        });
        delorean.push({
            "character": "Radio",
            "line": "This Saturday night, mostly clear, with some scattered clouds. Lows in the upper forties."
        });
        delorean.push({"character": "Doc", "line": "Are you sure about this storm?"});
        delorean.push({
            "character": "Marty",
            "line": "When could weathermen predict the weather, let alone the future."
        });
        delorean.push({
            "character": "Doc",
            "line": "You know Marty, I'm gonna be very sad to see you go. You've really mad a difference in my life, you've given me something to shoot for. Just knowing, that I'm gonna be around to se 1985, that I'm gonna succeed in this. That I'm gonna have a chance to travel through time. It's going to be really hard waiting 30 years before I could talk to you about everything that's happened in the past few days. I'm really gonna miss you, Marty."
        });
        delorean.push({"character": "Marty", "line": "I'm really gonna miss you. Doc, about the future-"});
        delorean.push({
            "character": "Doc",
            "line": "No, Marty, we've already agreed that having information about the future could be extremely dangerous. Even if your intentions are good, they could backfire drastically. Whatever you've got to tell me I'll find out through the natural course of time."
        });
        delorean.push({
            "character": "Marty",
            "line": "Dear Doctor Brown, on the night that I go back in time, you will be shot by terrorists. Please take whatever precautions are necessary to prevent this terrible disaster. Your friend, Marty."
        });
        delorean.push({"character": "Cop", "line": "Evening, Doctor Brown, what's with the wire?"});
        delorean.push({"character": "Doc", "line": "Oh, just a little weather experiment."});
        delorean.push({"character": "Cop", "line": "What you got under here?"});
        delorean.push({
            "character": "Doc",
            "line": "Oh no, don't touch that. That's some new specialized weather sensing equipment."
        });
        delorean.push({"character": "Cop", "line": "You got a permit for that?"});
        delorean.push({"character": "Doc", "line": "Of course I do. Just a second, let's see if I could find it."});
        delorean.push({"character": "Marty", "line": "Do you mind if we park for a while?"});
        delorean.push({"character": "Lorraine", "line": "That's a great idea. I'd love to park."});
        delorean.push({"character": "Marty", "line": "Huh?"});
        delorean.push({
            "character": "Lorraine",
            "line": "Well, Marty, I'm almost eighteen-years-old, it's not like I've never parked before."
        });
        delorean.push({"character": "Marty", "line": "What?"});
        delorean.push({"character": "Lorraine", "line": "Marty, you seem so nervous, is something wrong?"});
        delorean.push({"character": "Marty", "line": "No no. Lorraine, Lorraine, what are you doing?"});
        delorean.push({"character": "Lorraine", "line": "I swiped it from the old lady's liquor cabinet."});
        delorean.push({"character": "Marty", "line": "Yeah well, you shouldn't drink."});
        delorean.push({"character": "Lorraine", "line": "Why not?"});
        delorean.push({"character": "Marty", "line": "Because, you might regret it later in life."});
        delorean.push({
            "character": "Lorraine",
            "line": "Marty, don't be such a square. Everybody who's anybody drinks."
        });
        delorean.push({"character": "Marty", "line": "Jesus, you smoke too?"});
        delorean.push({"character": "Lorraine", "line": "Marty, you're beginning to sound just like my mother."});
        delorean.push({
            "character": "Marvin Berry",
            "line": "We're gonna take a little break but we'll be back in a while so, don't nobody go no where."
        });
        delorean.push({"character": "Lorraine", "line": "Marty, why are you so nervous?"});
        delorean.push({
            "character": "Marty",
            "line": "Lorraine, have you ever, uh, been in a situation where you know you had to act a certain way but when you got there, you didn't know if you could go through with it?"
        });
        delorean.push({"character": "Lorraine", "line": "Oh, you mean how you're supposed to act on a first date."});
        delorean.push({"character": "Marty", "line": "Ah well, sort of."});
        delorean.push({"character": "Lorraine", "line": "I think I know exactly what you mean."});
        delorean.push({"character": "Marty", "line": "You do?"});
        delorean.push({"character": "Lorraine", "line": "You know what I do in those situations?"});
        delorean.push({"character": "Marty", "line": "What?"});
        delorean.push({
            "character": "Lorraine",
            "line": "I don't worry. this is all wrong. I don't know what it is but when I kiss you, it's like kissing my brother. I guess that doesn't make any sense, does it?"
        });
        delorean.push({"character": "Marty", "line": "Well, you mean, it makes perfect sense."});
        delorean.push({
            "character": "Biff",
            "line": "You cost three-hundred buck damage to my car, you son-of-a-bitch. And I'm gonna take it out of your ass. Hold him."
        });
        delorean.push({"character": "Lorraine", "line": "Let him go, Biff, you're drunk."});
        delorean.push({
            "character": "Biff",
            "line": "Well looky what we have here. No no no, you're staying right here with me."
        });
        delorean.push({"character": "Lorraine", "line": "Stop it."});
        delorean.push({"character": "Biff", "line": "C'mon."});
        delorean.push({"character": "Lorraine", "line": "Stop it."});
        delorean.push({"character": "Biff", "line": "C'mon."});
        delorean.push({"character": "Marty", "line": "Leave her alone, you bastard."});
        delorean.push({
            "character": "Biff",
            "line": "You guys, take him in back and I'll be right there. Well c'mon, this ain't no peep show."
        });
        delorean.push({"character": "Skinhead", "line": "Let's put him in there."});
        delorean.push({"character": "3-D", "line": "Yeah."});
        delorean.push({"character": "Skinhead", "line": "That's for messing up my hair."});
        delorean.push({"character": "Starlighter", "line": "The hell you doing to my car?"});
        delorean.push({"character": "3-D", "line": "Hey beat it, spook, this don't concern you."});
        delorean.push({"character": "Marvin Berry", "line": "Who are you calling spook, pecker-wood."});
        delorean.push({
            "character": "Skinhead",
            "line": "Hey, hey listen guys. Look, I don't wanna mess with no reefer addicts, okay?"
        });
        delorean.push({"character": "Marty", "line": "C'mon, open up, let me out of here, Yo."});
        delorean.push({"character": "Marvin Berry", "line": "Lorenzo, where're you keys?"});
        delorean.push({"character": "Marty", "line": "The keys are in the trunk."});
        delorean.push({"character": "Marvin Berry", "line": "Say that again."});
        delorean.push({"character": "Marty", "line": "I said the keys are in here."});
        delorean.push({"character": "George", "line": "Hey you, get your damn hands off, oh."});
        delorean.push({"character": "Biff", "line": "I think you got the wrong car, McFly."});
        delorean.push({"character": "Lorraine", "line": "George, help me, please."});
        delorean.push({
            "character": "Biff",
            "line": "Just turn around, McFly, and walk away. Are you deaf, McFly? Close the door and beat it."
        });
        delorean.push({"character": "George", "line": "No, Biff, you leave her alone."});
        delorean.push({
            "character": "Biff",
            "line": "Alright, McFly, you're asking for it, and now you're gonna get it."
        });
        delorean.push({"character": "Lorraine", "line": "Biff, stop it. Biff, you're breaking his arm. Biff, stop."});
        delorean.push({
            "character": "Marvin Berry",
            "line": "Give me a hand, Lorenzo. Ow, dammit, man, I sliced my hand."
        });
        delorean.push({"character": "Marty", "line": "Who's are these?"});
        delorean.push({"character": "Starlighter", "line": "Thanks, thanks a lot."});
        delorean.push({
            "character": "Lorraine",
            "line": "You're gonna break his arm. Biff, leave him alone. Let him go. Let him go."
        });
        delorean.push({"character": "George", "line": "Are you okay?"});
        delorean.push({"character": "Girlfriend", "line": "Who is that guy."});
        delorean.push({"character": "Boyfriend", "line": "That's George McFly."});
        delorean.push({"character": "Girlfriend", "line": "That's George McFly?"});
        delorean.push({"character": "Marty", "line": "Excuse me."});
        delorean.push({"character": "Doc", "line": "The storm."});
        delorean.push({"character": "Marty", "line": "Hey guys, you gotta get back in there and finish the dance."});
        delorean.push({
            "character": "Starlighter",
            "line": "Hey man, look at Marvin's hand. He can't play with his hands like that, and we can't play without him."
        });
        delorean.push({
            "character": "Marty",
            "line": "Yeah well look, Marvin, Marvin, you gotta play. See that's where they kiss for the first time on the dance floor. And if there's no music, they can't dance, and if they can't dance, they can't kiss, and if they can't kiss, they can't fall in love and I'm history."
        });
        delorean.push({
            "character": "Marvin Berry",
            "line": "Hey man, the dance is over. Unless you know someone else who could play the guitar."
        });
        delorean.push({"character": "Marvin Berry", "line": "This is for all you lovers out there."});
        delorean.push({"character": "Lorraine", "line": "George, aren't you gonna kiss me?"});
        delorean.push({"character": "George", "line": "I, I don't know."});
        delorean.push({"character": "Obnoxious Kid", "line": "Scram, McFly."});
        delorean.push({"character": "Starlighter", "line": "Hey boy, are you alright?"});
        delorean.push({"character": "Marty", "line": "I can't play."});
        delorean.push({"character": "Lorraine", "line": "George. George."});
        delorean.push({"character": "Marty", "line": "George."});
        delorean.push({"character": "George", "line": "Excuse me."});
        delorean.push({"character": "Marvin Berry", "line": "Yeah man, that was good. Let's do another one."});
        delorean.push({"character": "Marty", "line": "Uh, well, I gotta go."});
        delorean.push({"character": "Marvin Berry", "line": "C'mon man, let's do something that really cooks."});
        delorean.push({
            "character": "Marty",
            "line": "Something that really cooks. Alright, alright this is an oldie, but uh, it's an oldie where I come from. Alright guys, let's do some blues riff in b, watch me for the changes, and uh, try and keep up, okay."
        });
        delorean.push({"character": "Boyfriend", "line": "Hey George, heard you laid out Biff, nice going."});
        delorean.push({"character": "Girlfriend", "line": "George: you ever think of running for class president?"});
        delorean.push({
            "character": "Marvin Berry",
            "line": "Chuck, Chuck, its' your cousin. Your cousin Marvin Berry, you know that new sound you're lookin for, well listen to this."
        });
        delorean.push({
            "character": "Marty",
            "line": "I guess you guys aren't ready for that yet. But your kids are gonna love it."
        });
        delorean.push({"character": "Marty", "line": "Lorraine."});
        delorean.push({"character": "Lorraine", "line": "Marty, that was very interesting music."});
        delorean.push({"character": "Marty", "line": "Uh, yeah."});
        delorean.push({
            "character": "Lorraine",
            "line": "I hope you don't mind but George asked if he could take me home."
        });
        delorean.push({"character": "Marty", "line": "Great good, good, Lorraine, I had a feeling about you two."});
        delorean.push({"character": "Lorraine", "line": "I have a feeling too."});
        delorean.push({
            "character": "Marty",
            "line": "Listen, I gotta go but I wanted to tell you that it's been educational."
        });
        delorean.push({"character": "Lorraine", "line": "Marty, will we ever see you again?"});
        delorean.push({"character": "Marty", "line": "I guarantee it."});
        delorean.push({
            "character": "George",
            "line": "Well, Marty, I want to thank you for all your good advise, I'll never forget it."
        });
        delorean.push({
            "character": "Marty",
            "line": "Right, George. Well, good luck you guys. Oh, one other thing, if you guys ever have kids and one of them when he's eight years old, accidentally sets fire to the living room rug, be easy on him."
        });
        delorean.push({"character": "George", "line": "Okay."});
        delorean.push({"character": "Lorraine", "line": "Marty, such a nice name."});
        delorean.push({
            "character": "Doc",
            "line": "Damn, where is that kid. Damn. Damn damn. You're late, do you have no concept of time?"
        });
        delorean.push({
            "character": "Marty",
            "line": "Hey c'mon, I had to change, you think I'm going back in that zoot suit? The old man really came through it worked."
        });
        delorean.push({"character": "Doc", "line": "What?"});
        delorean.push({
            "character": "Marty",
            "line": "He laid out Biff in one punch. I never knew he had it in him. He never stood up to Biff in his life."
        });
        delorean.push({"character": "Doc", "line": "Never?"});
        delorean.push({"character": "Marty", "line": "No, why, what's a matter?"});
        delorean.push({
            "character": "Doc",
            "line": "Alright, let's set your destination time. This is the exact time you left. I'm gonna send you back at exactly the same time. It's be like you never left. Now, I painted a white line on the street way over there, that's where you start from. I've calculated the distance and wind resistance fresh to active from the moment the lightning strikes, at exactly 7 minutes and 22 seconds. When this alarm goes off you hit the gas."
        });
        delorean.push({"character": "Marty", "line": "Right."});
        delorean.push({"character": "Doc", "line": "Well, I guess that's everything."});
        delorean.push({"character": "Marty", "line": "Thanks."});
        delorean.push({"character": "Doc", "line": "Thank you. In about thirty years."});
        delorean.push({"character": "Marty", "line": "I hope so."});
        delorean.push({
            "character": "Doc",
            "line": "Don't worry. As long as you hit that wire with the connecting hook at precisely 88 miles per hour, the instance the lightning strikes the tower, everything will be fine."
        });
        delorean.push({"character": "Marty", "line": "Right."});
        delorean.push({"character": "Doc", "line": "What's the meaning of this."});
        delorean.push({"character": "Marty", "line": "You'll find out in thirty years."});
        delorean.push({"character": "Doc", "line": "It's about the future, isn't it?"});
        delorean.push({"character": "Marty", "line": "Wait a minute."});
        delorean.push({
            "character": "Doc",
            "line": "It's information about the future isn't it. I warned you about this kid. The consequences could be disastrous."
        });
        delorean.push({
            "character": "Marty",
            "line": "Now that's a risk you'll have to take you're life depends on it."
        });
        delorean.push({"character": "Doc", "line": "No, I refuse to except the responsibility."});
        delorean.push({"character": "Marty", "line": "In that case, I'll tell you strait out."});
        delorean.push({
            "character": "Doc",
            "line": "Oh, great scott. You get the cable, I'll throw the rope down to you."
        });
        delorean.push({"character": "Marty", "line": "Right, I got it."});
        delorean.push({"character": "Doc", "line": "Ahh."});
        delorean.push({"character": "Marty", "line": "Doc."});
        delorean.push({"character": "Doc", "line": "C'mon, c'mon let's go."});
        delorean.push({"character": "Marty", "line": "Alright, take it up, go. Doc."});
        delorean.push({"character": "Doc", "line": "Huh?"});
        delorean.push({"character": "Marty", "line": "I have to tell you about the future."});
        delorean.push({"character": "Doc", "line": "Huh?"});
        delorean.push({"character": "Marty", "line": "I have to tell you about the future."});
        delorean.push({"character": "Doc", "line": "Ahh."});
        delorean.push({"character": "Marty", "line": "On the night I go back in time, you get- Doc."});
        delorean.push({"character": "Doc", "line": "Ohh, no."});
        delorean.push({"character": "Marty", "line": "No, Doc."});
        delorean.push({"character": "Doc", "line": "Look at the time, you've got less than 4 minutes, please hurry."});
        delorean.push({"character": "Marty", "line": "Yeah."});
        delorean.push({
            "character": "Marty",
            "line": "Dammit, Doc, why did you have to tear up that letter? If only I had more time. Wait a minute, I got all the time I want I got a time machine, I'll just go back and warn him. 10 minutes oughta do it. Time-circuits on, flux-capacitor fluxing, engine running, alright. No, no no no no, c'mon c'mon. C'mon c'mon, here we go, this time. Please, please, c'mon."
        });
        delorean.push({"character": "Doc", "line": "Ahh."});
        delorean.push({"character": "Marty", "line": "Doc."});
        delorean.push({"character": "Doc", "line": "Yoo."});
        delorean.push({"character": "Red", "line": "Crazy drunk drivers."});
        delorean.push({
            "character": "Marty",
            "line": "Wow, ah Red, you look great. Everything looks great. 1:24, I still got time. Oh my god. No, no not again, c'mon, c'mon. Hey. Libyans."
        });
        delorean.push({"character": "Marty", "line": "No, bastards."});
        delorean.push({"character": "Libyan", "line": "Go."});
        delorean.push({
            "character": "Marty",
            "line": "Doc, Doc. Oh, no. You're alive. Bullet proof vest, how did you know, I never got a chance to tell you. About all that talk about screwing up future events, the space time continuum."
        });
        delorean.push({"character": "Doc", "line": "Well, I figured, what the hell."});
        delorean.push({"character": "Marty", "line": "About how far ahead are you going?"});
        delorean.push({"character": "Doc", "line": "About 30 years, it's a nice round number."});
        delorean.push({"character": "Marty", "line": "Look me up when you get there, guess I'll be about 47."});
        delorean.push({"character": "Doc", "line": "I will."});
        delorean.push({"character": "Marty", "line": "Take care."});
        delorean.push({"character": "Doc", "line": "You too."});
        delorean.push({
            "character": "Marty",
            "line": "Alright, good-bye Einy. Oh, watch that re-entry, it's a little bumpy."
        });
        delorean.push({"character": "Doc", "line": "You bet."});
        delorean.push({"character": "Marty", "line": "What a nightmare."});
        delorean.push({
            "character": "Lynda",
            "line": "Oh, if Paul calls me tell him I'm working at the boutique late tonight."
        });
        delorean.push({
            "character": "David",
            "line": "Lynda, first of all, I'm not your answering service. Second of all, somebody named Greg or Craig called you just a little while ago."
        });
        delorean.push({"character": "Lynda", "line": "Now which one was it, Greg or Craig?"});
        delorean.push({"character": "David", "line": "I don't know, I can't keep up with all of your boyfriends."});
        delorean.push({"character": "Marty", "line": "What the hell is this?"});
        delorean.push({"character": "Lynda", "line": "Breakfast."});
        delorean.push({"character": "David", "line": "What did you sleep in your clothes again last night."});
        delorean.push({"character": "Marty", "line": "Yeah, yeah what are you wearing, Dave."});
        delorean.push({"character": "David", "line": "Marty, I always wear a suit to the office. You alright?"});
        delorean.push({"character": "Marty", "line": "Yeah."});
        delorean.push({"character": "Lorraine", "line": "I think we need a rematch."});
        delorean.push({"character": "George", "line": "Oh, oh a rematch, why, were you cheating?"});
        delorean.push({"character": "Lorraine", "line": "No."});
        delorean.push({"character": "George", "line": "Hello."});
        delorean.push({"character": "Lorraine", "line": "Good morning."});
        delorean.push({"character": "Marty", "line": "Mom, Dad."});
        delorean.push({"character": "Lorraine", "line": "Marty, are you alright?"});
        delorean.push({"character": "David", "line": "Did you hurt your head?"});
        delorean.push({"character": "Marty", "line": "you guys look great. Mom, you look so thin."});
        delorean.push({
            "character": "Lorraine",
            "line": "Why thank you, Marty. George. Good morning, sleepyhead, Good morning, Dave, Lynda"
        });
        delorean.push({"character": "David", "line": "Good morning, Mom."});
        delorean.push({
            "character": "Lynda",
            "line": "Good morning, Mom. Oh, Marty, I almost forgot, Jennifer Parker called."
        });
        delorean.push({
            "character": "Lorraine",
            "line": "Oh, I sure like her, Marty, she is such a sweet girl. Isn't tonight the night of the big date?"
        });
        delorean.push({"character": "Marty", "line": "What, what, ma?"});
        delorean.push({
            "character": "Lorraine",
            "line": "Well, aren't you going up to the lake tonight, you've been planning it for two weeks."
        });
        delorean.push({
            "character": "Marty",
            "line": "Well, ma, we talked about this, we're not gonna go to the lake, the car's wrecked."
        });
        delorean.push({"character": "George", "line": "Wrecked?"});
        delorean.push({"character": "David", "line": "Wrecked? When did this happen and-"});
        delorean.push({"character": "George", "line": "Quiet down, I'm sure the car is fine."});
        delorean.push({"character": "David", "line": "Why am I always the last one to know about these things."});
        delorean.push({
            "character": "George",
            "line": "See, there's Biff out there waxing it right now. Now, Biff, I wanna make sure that we get two coats of wax this time, not just one."
        });
        delorean.push({"character": "Biff", "line": "Just finishing up the second coat now."});
        delorean.push({"character": "George", "line": "Now Biff, don't con me."});
        delorean.push({
            "character": "Biff",
            "line": "I'm, I'm sorry, Mr. McFly, I mean, I was just starting on the second coat."
        });
        delorean.push({
            "character": "George",
            "line": "That Biff, what a character. Always trying to get away with something. Been on top of Biff ever since high school. Although, if it wasn't for him-"
        });
        delorean.push({"character": "Lorraine", "line": "We never would have fallen in love."});
        delorean.push({"character": "George", "line": "That's right."});
        delorean.push({
            "character": "Biff",
            "line": "Mr. McFly, Mr. McFly, this just arrived, oh hi Marty. I think it's your new book."
        });
        delorean.push({"character": "Lorraine", "line": "Ah, honey, your first novel."});
        delorean.push({
            "character": "George",
            "line": "Like I always told you, if you put your mind to it you could accomplish anything."
        });
        delorean.push({
            "character": "Biff",
            "line": "Oh, oh Marty, here's you keys. You're all waxed up, ready for tonight."
        });
        delorean.push({"character": "Marty", "line": "Keys?"});
        delorean.push({"character": "Jennifer", "line": "How about a ride, Mister?"});
        delorean.push({
            "character": "Marty",
            "line": "Jennifer, oh are you a sight for sore eyes. Let me look at you."
        });
        delorean.push({"character": "Jennifer", "line": "Marty, you're acting like you haven't seen me in a week."});
        delorean.push({"character": "Marty", "line": "I haven't"});
        delorean.push({"character": "Jennifer", "line": "You okay, is everything alright?"});
        delorean.push({"character": "Marty", "line": "Aw yeah, everything is great."});
        delorean.push({"character": "Doc", "line": "Marty you gotta come back with me."});
        delorean.push({"character": "Marty", "line": "Where?"});
        delorean.push({"character": "Doc", "line": "Back to the future."});
        delorean.push({"character": "Marty", "line": "Wait a minute, what are you doing, Doc?"});
        delorean.push({"character": "Doc", "line": "I need fuel. Go ahead, quick, get in the car."});
        delorean.push({
            "character": "Marty",
            "line": "No no no, Doc, I just got here, okay, Jennifer's here, we're gonna take the new truck for a spin."
        });
        delorean.push({"character": "Doc", "line": "Well, bring her along. This concerns her too."});
        delorean.push({
            "character": "Marty",
            "line": "Wait a minute, Doc. What are you talking about? What happens to us in the future? What do we become assholes or something?"
        });
        delorean.push({
            "character": "Doc",
            "line": "No no no no no, Marty, both you and Jennifer turn out fine. It's your kids, Marty, something has got to be done about your kids."
        });
        delorean.push({
            "character": "Marty",
            "line": "Hey, Doc, we better back up, we don't have enough roads to get up to 88."
        });
        delorean.push({"character": "Doc", "line": "Roads? Where we're going we don't need roads."});
        delorean.push({"character": "Name", "line": "Samantha"});
        delorean.push({"character": "Name", "line": "tonyza18"});
        delorean.push({"character": "Name", "line": "Richard "});
        delorean.push({"character": "Name", "line": "Syahril "});
        delorean.push({"character": "Name", "line": "Carina "});
        delorean.push({"character": "Name", "line": "Hans "});
        delorean.push({"character": "Name", "line": "Sami "});
        delorean.push({"character": "Name", "line": "Taranjit"});
        delorean.push({"character": "Name", "line": "Andrej"});
        delorean.push({"character": "Name", "line": "Salvo"});
        delorean.push({"character": "Name", "line": "Zulkefli"});
        delorean.push({"character": "Name", "line": "Carlsson"});
        delorean.push({"character": "Name", "line": "Bauer"});
        delorean.push({"character": "Name", "line": "Georg"});
        delorean.push({"character": "Name", "line": "Benyoussef"});
        delorean.push({"character": "Name", "line": "Bauer"});
        delorean.push({"character": "Name", "line": "Bauer"});
        delorean.push({"character": "Name", "line": "Smith"});

        delorean.push({"character": "product_category", "line": "Cellphones & Smartphones"});
        delorean.push({"character": "product_category", "line": "Accessory Bundles"});
        delorean.push({"character": "product_category", "line": "Audio Docks & Speakers "});
        delorean.push({"character": "product_category", "line": "Chargers & Cradles "});
        delorean.push({"character": "product_category", "line": "Faceplates, Decals & Stickers "});
        delorean.push({"character": "product_category", "line": "FM Transmitters "});
        delorean.push({"character": "product_category", "line": "Manuals & Guides "});
        delorean.push({"character": "product_category", "line": "Mounts & Holders"});
        delorean.push({"character": "product_category", "line": "Port Dust Covers"});
        delorean.push({"character": "product_category", "line": "Port Dust Covers"});

        delorean.push({"character": "product", "line": "Samantha"});
        delorean.push({"character": "product", "line": "tonyza18"});
        delorean.push({"character": "product", "line": "Richard "});
        delorean.push({"character": "product", "line": "Syahril "});
        delorean.push({"character": "product", "line": "Carina "});
        delorean.push({"character": "product", "line": "Hans "});
        delorean.push({"character": "product", "line": "Sami "});
        delorean.push({"character": "product", "line": "Taranjit"});
        delorean.push({"character": "product", "line": "Andrej"});
        delorean.push({"character": "product", "line": "Salvo"});
        delorean.push({"character": "product", "line": "Zulkefli"});
        delorean.push({"character": "product", "line": "Carlsson"});
        delorean.push({"character": "product", "line": "Bauer"});
        delorean.push({"character": "product", "line": "Georg"});
        delorean.push({"character": "product", "line": "Benyoussef"});
        delorean.push({"character": "product", "line": "Bauer"});
        delorean.push({"character": "product", "line": "Bauer"});
        delorean.push({"character": "product", "line": "Smith"});

        function mrFusion() {
            var sentencesPara = opts.perPara;
            var length = delorean.length;
            var mcfly = new Array();
            for (var i = 0; i < length; i++) {
                if (opts.character.toLowerCase() != 'all' && opts.character.toLowerCase() != '') {
                    if (delorean[i]['character'].toLowerCase() == opts.character.toLowerCase()) mcfly.push(delorean[i]['line']);
                } else mcfly.push(delorean[i]['line']);
            }
            var max_num = mcfly.length;
            if (max_num == 0 && opts.character.toLowerCase() != '') return opts.character + " has no lines!";

            var diff = max_num - min_num;

            if (opts.type == 'paragraphs') howmany = howmany * sentencesPara;

            var einstein = "";
            var sentenceCount = 0;
            for (var i = 0; i < howmany; i++) {
                sentenceCount++;
                rnd_number = Math.floor(Math.random() * diff + min_num);
                if ((opts.tag != '' && opts.type != 'paragraphs') || (opts.tag != '' && opts.type == 'paragraphs' && sentenceCount == 1)) {
                    einstein += "<" + opts.tag + ">";
                }
                einstein += mcfly[rnd_number];
                if (opts.type == 'paragraphs') {
                    if (sentenceCount == sentencesPara) {
                        if (opts.tag != '') {
                            einstein += "</" + opts.tag + ">";
                        }
                        einstein += "\n\n";
                        sentenceCount = 0;
                    } else einstein += " ";
                } else {
                    if (opts.tag != '') {
                        einstein += "</" + opts.tag + ">";
                    }
                    einstein += "\n\n";
                }
            }

            switch (opts.type) {
                case "words":
                {
                    var numOfWords = opts.amount;
                    numOfWords = parseInt(numOfWords);
                    var list = new Array();
                    var wordList = new Array();
                    wordList = einstein.split(' ');
                    var iParagraphCount = 0;
                    var iWordCount = 0;
                    while (list.length < numOfWords) {
                        if (iWordCount > wordList.length) {
                            iWordCount = 0;
                            iParagraphCount++;
                            if (iParagraphCount + 1 > delorean.length) {
                                iParagraphCount = 0;
                            }
                            wordList = delorean[iParagraphCount].split(' ');
                            wordList[0] = "\n\n" + wordList[0];
                        }
                        list.push(wordList[iWordCount]);
                        iWordCount++;
                    }
                    einstein = list.join(' ');
                    break;
                }
                case 'characters':
                {
                    var outputString = '';
                    var numOfChars = opts.amount;
                    numOfChars = parseInt(numOfChars);
                    var tempString = mcfly.join("\n\n");
                    while (outputString.length < numOfChars) {
                        outputString += tempString;
                    }
                    einstein = outputString.substring(0, numOfChars);
                    break;
                }
            }
            return einstein;
        }

        return this.each(function () {
            $this = $(this);
            var markup = mrFusion();
            if ($this.is('input')) {
                $this.val(markup);
            } else {
                $this.html(markup);
            }

        });
    };

})(jQuery);