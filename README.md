#CASS Snap
Snap changes manifest
```
│   config.php
│		- Moved headroom.js out of default list, to optionally load based on
│         theme setting. (Either headroom is used, or breadcrumb is.)
│		- Optionally load completion and animation javascript library;
│         "TweenMax", based on theme setting.
│		- Optionally run csspostprocess function based on a theme setting
│
│   coursepageredirect.php
│       - New file to optionally redirect a non-admin student away from the
│         site index and my dashboard to a course they have accessed or are
│         enrolled in, based on favouring the most recent timestamp.
│
│   Gruntfile.js
│       - Fixed bug where the moodle cache purge would not work on windows
│
│   lib.php
│       - Include external file: coursepageredirect.php.
│
│   renderers.php
│       - Include two new renderers:
│         core_question_renderer and mod_quiz_renderer
│
│   rest.php
│       - Change to give the PAGE global awareness of the associated course
│         and course activity module
│
│   settings.php
│       - Added some settings headers (navigationheading & footerheading &
│         functionalheading). Reorganised some settings into categories.
│       Added the following settings:
│           - hidequiznavigation: When this setting is enabled, the quiz
│             navigation output is not generated for non editors
│           - breadcrumbsinnav: Position the breadcrumbs inside the navigation
│             menu to save on vertical space
│           - fixheadertotopofpage: If checked, the navigation header will
│             follow the top of the screen. If unchecked the header will
│             normally scroll off the top of the screen.
│           - collapsecompletedactivities : When enabled, completed activities
│             on the course page are collapsed
│           - embedcurrentactivity:  content from the current activity into the
│             course page
│           - coursepageredirect: Redirect user to course page on login.
│           - nextactivityinfooter: On completion of activty, show Next
│             Activity in footer. Activity Completion must be enabled.
│           - nextactivitymodaldialog: On completion of activty, popup modal
│             dialog with link to next activity. Activity Completion must be
│             enabled.
│           - nextactivitymodaldialogtolerance: Number of seconds after
│             completion event to continue generating the modal dialog.
│             Default 30.
│           - nextactivitymodaldialogdelay: Number of milliseconds after page
│             load to pop the completion modal. Default 2000.
│           - questionsemanticactivation: If this setting is enabled a
│             truefalse question type is rendered "True / False" prior to the
│             question text rather than: "Question 1"
│           - displayquestionxofy: Display: "Question x of y" before each
│             question in a quiz activity
│           - highlightfirstactivityinsection: Visual signal to indicate to the
│             user that they are on the first activity in the section
│           - copyrightnotice: You may optionally specify a custom copyright
│             notice here. If left blank the default notice will apply.
│           - fontloader: If you need to load a custom font resource, you can
│             do it here.
│           - csspostprocesstoggle: Processes the background image, custom css
│             and applies the bootswatch
│           
├───classes
│   │   local.php
│   │       - Added entry point function: render_completion_footer() which 
│   │         generates HTML around data populated from the function: 
│   │         get_completion_footer()
│   │         If we're on a 'mod' page, retrieve the mod object and check it's
│   │         completion state in order to conditionally pop a completion modal
│   │         and show a link to the next activity in the footer.
│   │   
│   └───C
│           hsuforummod_controller.php
│               - A new controller for hsuforum to generate completion data.
│
├───javascript
│       breadcrumb.js
│           - Hides and unhides headings in the breadcrumb when
│             'fixheadertotopofpage' is set and the user has scrolled past
│             a relevant heading.
│
│       completion.js
│           - Includes javascript functions relevant for activity completion
│             navigation rendering and progression.
│
│       module.js
│           - Modified init signature to add awareness of  the theme settings
│             and current activity module to the javascript module
│           - addPopCompletion conditionally pops completion status based on
│             the mod completion and type.
│	
│       snap.js
│           - Added javascript listener to toggle the chevron glyph icon on 
│             bootstrap collapsible selectors.
│           - Conditionally use headroom.js based on theme setting: 
│             fixheadertotopofpage.
│           - Add listener to submit button for hsuforum inline replies to bind
│             to the completion status.
│
│       TweenMax.js
│           - Javascript tweening and animation library for animated completion  
│       
├───lang
│   └───en
│           theme_snap.php
│           
├───layout
│       course.php
│           - display breadcrumbs on the course page or in the header based on
│             a theme setting.
│
│       default.php
│           - Allows the page to be generated without any additional HTML
│             wrapping based on a theme setting and a URL parameter.
│           - If the embedcurrentactivity theme setting is set and a URL
│             parameter of embed is set (to true), output nothing but the
│             activity mod content.
│           - Adds additional CSS class highlightfirstactivityinsection to 
│             activity module if it is the first module in the section.
│           - Display breadcrumbs on the course page or in the header based on
│             a theme setting.
│
│       footer.php
│           - Added call to render the completion footer/
│           - Render standard snap copyright notice or custom copyright notice
│             based on a theme setting.
│
│       header.php
│           - Changed standard snap font loader to use the font loader based
│             on a theme setting
│
│       nav.php
│           - Display breadcrumbs inside the navigation header based on a theme
│           - Display breadcrumbs on the course page or in the header based on
│             setting.
│       
├───less
│   │   
│   └───bootswatch
│           - All style changes isolated inside the bootswatch
│           snap-core.less
│           snap-course.less
│           snap-forms.less
│           snap-user-bootswatch.less
│           snap-variables.less
│ 
├───pix_core
│   │       
│   └───i
│           grade_correct.svg
│               - Image to override the default question correct image.
│
│           grade_incorrect.svg
│               - Image to override the default question incorrect image.
│
├───renderers
│       core_question_renderer.php
│       - Question renderer to replace numbered question headings e.g.
│         "Question 1" etc. with meaningful information about how to
│         to answer the question based on the question type. e.g. "Fill in the
│         blank" or "True or False". Implemented via overriding the function:
│         info with a slight change based on a theme setting.
│       - Question renderer to replace the function: question 1uestion x of y
│         so the question output is prepended with the string: Question x of y,
│         based on a theme setting.
│
│       course_renderer.php
│           - Removed many additional css classes from specific activity types
│             to align with a google material design stepper. This has been
│             done to simplify the user interface. We believe it is already too
│             busy.
│           - Added generation and output of a material design stepper.
│           - Allowed for the current activity  (first uncompleted activity in
│             section) to be embedded (via local scraping) into the expanded
│             item.
│
│       mod_quiz_renderer.php
│           - Quiz navigation panel makes the screen too "busy" for normal
│             users. This renderer overrides the navigation panel function to
│             not disyplay the navigation panel based on a theme setting and
│             whether the current user has the capability to manage quizzes.
│
│       snap_shared.php
│           - Modified requires->js_init_call of snap javascript module
│             function signature to include some theme settings and some
│             activity information.
│
```

			
			

#Snap
Snap is a Moodle theme that makes online learning an enjoyable and intuitive experience for learners and educators.

Snap’s user-friendly design removes barriers to online learning, enabling you to create the modern, engaging experience users expect on the web today. Its intuitive layout is optimised for online learning, focusing on the things that matter - your learning activities and content.

Snap’s easy to use navigation gives users an elegant way to perform frequent tasks - all your courses, deadlines, messages and feedback are always one click or tap away to save you time.

Working seamlessly across every device - from desktop to mobile, Snap’s responsive Twitter Bootstrap based framework provides a consistent, professional experience for learning whenever and wherever you want to learn.

#Technology

Built with Bootstrap 3 and jQuery

#Moodlerooms
This plugin was contributed by the Moodlerooms Product Development team.  Moodlerooms is an education technology company dedicated to bringing excellent online teaching to institutions across the globe.  We serve colleges and universities, schools and organizations by supporting the software that educators use to manage and deliver instructional content to learners in virtual classrooms.  Moodlerooms is headquartered in Baltimore, MD.  We are proud to be a Moodle Partner company.