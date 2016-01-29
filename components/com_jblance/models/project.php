<?php
/**
 * @company        :    BriTech Solutions
 * @created by    :    JoomBri Team
 * @contact        :    www.joombri.in, support@joombri.in
 * @created on    :    23 March 2012
 * @file name    :    models/project.php
 * @copyright   :    Copyright (C) 2012. All rights reserved.
 * @license     :    GNU General Public License version 2 or later
 * @author      :    Faisel
 * @description    :    Entry point for the component (jblance)
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class JblanceModelProject extends JModelLegacy
{

    function getEditProject()
    {

        $app =& JFactory::getApplication();
        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();
        $id = $app->input->get('id', 0, 'int');

        $config =& JblanceHelper::getConfig();
        $currencysym = $config->currencySymbol;

        //check if the user's plan has expired or not approved. If so, do not allow him to post new project
        $planStatus = JblanceHelper::planStatus($user->id);
        if (($id == 0) && ($planStatus == 1 || $planStatus == 2)) {
            $msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_DO_OPERATION_NO_ACTIVE_SUBSCRIPTION');
            $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
            $app->redirect($link, $msg, 'error');
            return false;
        }

        //check if the user has enough fund to post new projects. This should be checked for new projects only
        $plan = JblanceHelper::whichPlan($user->id);
        $chargePerProject = $plan->buyChargePerProject;

        if (($chargePerProject > 0) && ($id == 0)) {
            $totalFund = JblanceHelper::getTotalFund($user->id);
            if ($totalFund < $chargePerProject) {
                $msg = JText::sprintf('COM_JBLANCE_BALANCE_INSUFFICIENT_TO_POST_PROJECT', $currencysym, $chargePerProject);
                $link = JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
                $app->redirect($link, $msg, 'error');
                return false;
            }
        }

        $row =& JTable::getInstance('project', 'Table');
        $row->load($id);

        $query = 'SELECT * FROM #__jblance_project_file WHERE project_id=' . $id;
        $db->setQuery($query);
        $projfiles = $db->loadObjectList();

        $query = "SELECT * FROM #__jblance_custom_field " .
            "WHERE published=1 AND field_for=" . $db->quote('project') . " " .
            "ORDER BY ordering";
        $db->setQuery($query);
        $fields = $db->loadObjectList();

        $return[0] = $row;
        $return[1] = $projfiles;
        $return[2] = $fields;
        return $return;
    }

    function bind_project_to_my_website()
    {
        $list_category = '15-.NET

792-360-degree video

395-3D Animation

505-3D Design

394-3D Modelling

630-3D Printing

93-3D Rendering

370-3ds Max

571-4D

194-Academic Writing

53-Accounting

340-ActionScript

223-Active Directory

378-Ad Planning &amp; Buying

756-Adobe Air

169-Adobe Dreamweaver

12-Adobe Flash

168-Adobe InDesign

878-Adobe Lightroom

486-Adobe LiveCycle Designer

369-Advertisement Design

100-Advertising

330-Aeronautical Engineering

430-Aerospace Engineering

449-Affiliate Marketing

511-Afrikaans

171-After Effects

480-Agile Development

654-Air Conditioning

755-Airbnb

51-AJAX

575-Albanian

92-Algorithm

753-Alibaba

718-Amazon Fire

306-Amazon Kindle

319-Amazon Web Services

801-AMQP

497-Analytics

59-Android

413-Android Honeycomb

793-Android Wear SDK

704-Angular.js

107-Animation

804-Antenna Services

331-Anything Goes

215-Apache

313-Apache Solr

403-Appcelerator Titanium

725-Apple Compressor

724-Apple Logic Pro

726-Apple Motion

415-Apple Safari

787-Apple Watch

661-Appliance Installation

805-Appliance Repair

554-Arabic

440-Arduino

740-Argus Monitoring Software

250-Article Rewriting

347-Article Submission

174-Articles

126-Arts &amp; Crafts

214-AS400 &amp; iSeries

806-Asbestos Removal

5-ASP

690-ASP.NET

807-Asphalt

448-Assembly

125-Asterisk PBX

429-Astrophysics

808-Attic Access Ladders

693-Audio Production

28-Audio Services

316-Audit

785-Augmented Reality

271-AutoCAD

745-Autodesk Inventor

589-Autodesk Revit

383-AutoHotkey

190-Automotive

809-Awnings

279-Azure

596-backbone.js

244-Balsamiq

810-Balustrading

811-Bamboo Flooring

26-Banner Design

523-Basque

642-Bathroom

556-Bengali

494-Big Data

424-BigCommerce

769-Binary Analysis

240-Biology

144-Biotechnology

588-Bitcoin

582-Biztalk

133-Blackberry

75-Blog

356-Blog Design

355-Blog Install

796-Bluetooth Low Energy (BLE)

273-BMC Remedy

367-Book Writing

691-Bookkeeping

135-Boonex Dolphin

602-Bootstrap

586-Bosnian

781-Bower

257-BPO

812-Brackets

452-Brain Storming

97-Branding

813-Bricklaying

461-Broadcast Engineering

310-Brochure Design

608-BSD

641-Building

84-Building Architecture

814-Building Certifiers

815-Building Consultants

816-Building Designer

817-Building Surveyors

551-Bulgarian

94-Bulk Marketing

245-Business Analysis

278-Business Cards

373-Business Catalyst

594-Business Coaching

149-Business Plans

678-Business Writing

213-Buyer Sourcing

6-C Programming

106-C# Programming

320-C++ Programming

76-CAD/CAM

235-CakePHP

685-Call Center

730-Call Control XML

312-Capture NX2

234-Caricature &amp; Cartoons

644-Carpentry

818-Carpet Repair &amp; Laying

819-Carports

206-Cartography &amp; Maps

637-Carwashing

606-CasperJS

779-Cassandra

514-Catalan

626-Catch Phrases

820-Ceilings

821-Cement Bonding Agents

455-CGI

591-Chef Configuration Management

187-Chemical Engineering

275-Chordiant

583-Christmas

256-Chrome OS

733-Cinema 4D

612-Circuit Design

128-Cisco

186-Civil Engineering

349-Classifieds Posting

473-Clean Technology

822-Cleaning Carpet

823-Cleaning Domestic

824-Cleaning Upholstery

338-Climate Sciences

825-Clothesline

89-Cloud Computing

120-CMS

826-Coating Materials

276-COBOL

141-Cocoa

237-Codeigniter

11-Cold Fusion

827-Columns

634-Commercial Cleaning

366-Commercials

609-Communications

627-Compliance

422-Computer Graphics

858-Computer Help

73-Computer Security

229-Concept Design

650-Concreting

242-Construction Monitoring

662-Content Writing

182-Contracts

736-Conversion Rate Optimisation

451-Cooking &amp; Recipes

859-Cooking / Baking

375-Copy Typing

21-Copywriting

232-Corporate Identity

828-Courses

262-Covers &amp; Packaging

377-CRE Loaded

624-Creative Design

623-Creative Writing

147-CRM

528-Croatian

263-Cryptography

484-CS-Cart

77-CSS

380-CubeCart

286-CUDA

671-Customer Service

79-Customer Support

515-Czech

597-D3.js

829-Damp Proofing

517-Danish

743-Dari

427-Dart

39-Data Entry

334-Data Mining

36-Data Processing

761-Data Science

717-Data Warehousing

472-Database Administration

709-Database Development

673-Database Programming

252-Dating

800-DDS

620-Debian

481-Debugging

640-Decking

865-Decoration

632-Delivery

34-Delphi

830-Demolition

220-Desktop Support

611-Digital Design

639-Disposals

113-Django

224-DNS

438-DOS

130-DotNetNuke

652-Drafting

831-Drains

595-Drones

98-Drupal

532-Dutch

397-Dynamics

251-eBay

160-eBooks

137-eCommerce

104-Editing

109-Education &amp; Tutoring

618-edX

728-Elasticsearch

406-eLearning

184-Electrical Engineering

646-Electricians

159-Electronic Forms

43-Electronics

694-Email Handling

569-Email Marketing

191-Embedded Software

760-Ember.js

326-Employment Law

463-Energy

42-Engineering

464-Engineering Drawing

519-English (UK)

561-English (US)

677-English Grammar

675-English Spelling

614-Entrepreneurship

832-Equipment Hire

110-Erlang

272-ERP

735-Estonian

754-Etsy

290-Event Planning

864-Event Staffing

659-Excavation

55-Excel

598-Express JS

155-Expression Engine

833-Extensions &amp; Additions

419-Face Recognition

74-Facebook Marketing

127-Fashion Design

450-Fashion Modeling

653-Fencing

834-Feng Shui

175-Fiction

348-FileMaker

524-Filipino

390-Final Cut Pro

346-Finale / Sibelius

179-Finance

680-Financial Analysis

227-Financial Markets

835-Financial Planning

86-Financial Research

193-Finite Element Analysis

542-Finnish

393-Firefox

311-Flash 3D

460-Flashmob

78-Flex

836-Floor Coatings

655-Flooring

412-Flyer Design

837-Flyscreens

638-Food Takeaway

264-Format &amp; Layout

308-Fortran

164-Forum Posting

150-Forum Software

762-FPGA

838-Frames &amp; Trusses

105-Freelance

344-FreelancerAPI

526-French

525-French (Canadian)

399-Fundraising

635-Furniture Assembly

458-Furniture Design

457-Game Consoles

60-Game Design

668-Game Development

752-GameSalad

428-Gamification

722-GarageBand

657-Gardening

839-Gas Fitting

200-Genealogy

672-General Office

466-Genetic Engineering

321-Geolocation

239-Geology

506-Geospatial

518-German

156-Ghostwriting

741-Git

840-Glass / Mirror &amp; Glazing

248-Golang

302-Google Adsense

66-Google Adwords

298-Google Analytics

131-Google App Engine

790-Google Cardboard

414-Google Chrome

357-Google Earth

773-Google Maps API

418-Google Plus

359-Google SketchUp

485-Google Web Toolkit

699-Google Webmaster Tools

705-Google Website Optimizer

771-GoPro

266-GPGPU

374-GPS

246-Grant Writing

20-Graphic Design

483-Grease Monkey

550-Greek

751-Growth Hacking

774-Grunt

841-Guttering

495-Hadoop

649-Handyman

425-Haskell

777-HBase

300-Health

842-Heating Systems

553-Hebrew

689-Helpdesk

772-Heroku

555-Hindi

417-Hire me

468-History

776-Hive

843-Home Automation

162-Home Design

866-Home Organization

874-HomeKit

844-Hot Water

633-House Cleaning

631-Housework

274-HP Openview

335-HTML

323-HTML5

145-Human Resources

435-Human Sciences

531-Hungarian

795-iBeacon

376-IBM Tivoli

734-IBM Websphere Transformation Tool

368-Icon Design

225-IIS

845-IKEA Installation

170-Illustration

70-Illustrator

423-Imaging

721-iMovie

512-Indonesian

61-Industrial Design

469-Industrial Engineering

433-Infographics

867-Inspections

758-Instagram

868-Installation

351-Instrumentation

154-Insurance

277-Interior Design

846-Interiors

25-Internet Marketing

692-Internet Research

711-Internet Security

404-Interspire

289-Inventory Management

706-Investment Research

410-Invitation Design

879-Ionic Framework

307-iPad

58-iPhone

337-ISO9001

529-Italian

750-ITIL

45-J2EE

295-J2ME

628-Jabber

533-Japanese

7-Java

112-JavaFX

9-Javascript

578-JDF

574-Jewellery

54-Joomla

343-jQuery / Prototype

8-JSP

573-Kannada

398-Kinect

643-Kitchen

600-Knockout.js

527-Korean

713-Label Design

391-LabVIEW

482-Landing Pages

847-Landscaping

647-Landscaping &amp; Gardening

669-Laravel

254-LaTeX

576-Latvian

861-Laundry and Ironing

660-Lawn Mowing

177-Leads

798-Leap Motion SDK

24-Legal

183-Legal Research

708-Legal Writing

775-LESS/Sass/SCSS

593-Life Coaching

848-Lighting

465-Linear Programming

40-Link Building

447-Linkedin

31-Linux

701-Lisp

530-Lithuanian

849-Locksmith

212-Logistics &amp; Shipping

32-Logo Design

392-Lotus Notes

142-Mac OS

585-Macedonian

292-Machine Learning

90-Magento

791-Magic Leap

768-Mailchimp

584-Makerbot

513-Malay

560-Malayalam

720-Maltese

178-Management

83-Manufacturing

303-Manufacturing Design

496-Map Reduce

621-MariaDB

339-Market Research

82-Marketing

401-Marketplace Service

185-Materials Engineering

329-Mathematics

63-Matlab &amp; Mathematica

173-Maya

166-Mechanical Engineering

192-Mechatronics

176-Medical

195-Medical Writing

148-Metatrader

488-Metro

747-Microbiology

111-Microcontroller

291-Microsoft

124-Microsoft Access

222-Microsoft Exchange

249-Microsoft Expression

767-Microsoft Hololens

686-Microsoft Office

679-Microsoft Outlook

695-Microsoft SQL Server

353-Microstation

664-Millwork

328-Mining Engineering

151-MLM

91-MMORPG

716-Mobile App Testing

44-Mobile Phone

230-MODx

770-MonetDB

507-Moodle

850-Mortgage Brokering

474-Motion Graphics

856-Moving

799-MQTT

172-Music

434-MVC

478-MYOB

96-MySpace

305-MySQL

467-Nanotechnology

293-Natural Language

683-Network Administration

362-Newsletters

301-Nginx

446-Ning

500-node.js

280-Nokia

534-Norwegian

287-NoSQL Couch &amp; Mongo

322-Nutrition

143-Objective C

420-OCR

789-Oculus Mobile SDK

674-Online Writing

565-Open Cart

784-OpenBravo

499-OpenCL

498-OpenGL

748-OpenVMS

118-Oracle

218-Order Processing

231-OSCommerce

714-Package Design

863-Packing &amp; Shipping

645-Painting

134-Palm

870-Papiamento

744-Parallax Scrolling

389-Parallels Automation

386-Parallels Desktop

226-Patents

384-Pattern Making

421-Pattern Matching

658-Pavement

138-Paypal API

288-Payroll

265-PCB Layout

158-PDF

782-PencilBlue CMS

314-Pentaho

132-PeopleSoft

802-Periscope

4-Perl

441-Personal Development

851-Pest Control

857-Pet Sitting

456-Petroleum Engineering

217-Phone Support

577-PhoneGap

204-Photo Editing

27-Photography

57-Photoshop

108-Photoshop Coding

284-Photoshop Design

3-PHP

509-Physics

350-PICK Multivalue DB

869-Pickup

501-Pinterest

852-Piping

163-PLC &amp; SCADA

387-Plesk

872-Plugin

648-Plumbing

431-Poetry

535-Polish

537-Portuguese

536-Portuguese (Brazil)

476-Post-Production

408-Poster Design

607-PostgreSQL

80-Powerpoint

477-Pre-production

436-Presentations

405-Press Releases

304-Prestashop

444-Prezi

157-Print

746-Procurement

203-Product Descriptions

324-Product Design

352-Product Management

62-Product Sourcing

37-Project Management

241-Project Scheduling

294-Prolog

48-Proofreading

492-Property Development

325-Property Law

493-Property Management

491-Proposal/Bid Writing

243-Protoshare

247-PSD to HTML

268-PSD2CMS

354-Psychology

297-Public Relations

114-Publishing

557-Punjabi

590-Puppet

13-Python

572-QlikView

738-Qualtrics Survey Platform

201-Quantum

259-QuarkXPress

580-QuickBase

180-Quickbooks &amp; Quicken

601-R Programming Language

759-React.js

443-Real Estate

221-REALbasic

207-Recruitment

622-Red Hat

698-Redis

470-Remote Sensing

853-Removalist

732-Renewable Energy Design

123-Report Writing

47-Research

697-RESTful

360-Resumes

202-Reviews

665-Risk Management

439-Robotics

382-Rocket Engine

538-Romanian

651-Roofing

459-RTOS

616-Ruby

50-Ruby on Rails

539-Russian

603-RWD

64-Sales

712-Salesforce App Development

318-Salesforce.com

315-Samsung

794-Samsung Accessory SDK

267-SAP

341-SAS

592-Scala

703-Scheme

85-Scientific Research

453-Screenwriting

16-Script Install

749-Scrum

479-Scrum Development

854-Sculpturing

715-Search Engine Marketing

364-Sencha / YahooUI

38-SEO

552-Serbian

860-Sewing

146-Sharepoint

270-Shell Script

502-Shopify

371-Shopify Templates

636-Shopping

56-Shopping Carts

432-Short Stories

579-Siebel

161-Silverlight

547-Simplified Chinese (China)

610-Slogans

540-Slovakian

541-Slovenian

400-Smarty PHP

757-Snapchat

136-Social Engine

663-Social Media Marketing

65-Social Networking

599-Socket IO

116-Software Architecture

613-Software Development

167-Software Testing

333-Solaris

139-Solidworks

617-Sound Design

521-Spanish

522-Spanish (Spain)

780-Spark

361-Speech Writing

742-Sphinx

189-Sports

737-SPSS Statistics

68-SQL

696-SQLite

875-Squarespace

615-Startups

196-Stationery Design

707-Statistical Analysis

119-Statistics

877-Steam API

409-Sticker Design

763-Stripe

188-Structural Engineering

503-SugarCRM

211-Supplier Sourcing

764-Surfboard Design

543-Swedish

727-Swift

117-Symbian

385-Symfony PHP

30-System Admin

365-T-Shirts

558-Tamil

372-TaoBao API

871-Tattoo Design

181-Tax

327-Tax Law

216-Technical Support

103-Technical Writing

471-Telecommunications Engineering

49-Telemarketing

681-Telephone Handling

559-Telugu

269-Templates

208-Test Automation

67-Testing / QA

396-TestStand

563-Textile Engineering

544-Thai

656-Tiling

682-Time Management

797-Tizen SDK for Wearables

549-Traditional Chinese (Hong Kong)

548-Traditional Chinese (Taiwan)

46-Training

121-Transcription

22-Translation

238-Travel Writing

102-Troubleshooting

462-Tumblr

546-Turkish

72-Twitter

731-TYPO3

99-Typography

619-Ubuntu

684-Ukranian

581-Umbraco

101-UML Design

407-Unity 3D

336-UNIX

562-Urdu

317-Usability Testing

710-User Experience Design

115-User Interface / IA

332-Valuation &amp; Appraisal

700-VB.NET

233-vBulletin

71-Verilog / VHDL

719-VertexFX

283-Video Broadcasting

688-Video Editing

676-Video Production

52-Video Services

198-Video Upload

475-Videography

545-Vietnamese

437-Viral Marketing

129-Virtual Assistant

253-Virtual Worlds

363-Virtuemart

388-Virtuozzo

381-Visa / Immigration

445-Visual Arts

14-Visual Basic

504-Visual Basic for Apps

379-Visual Foxpro

667-VMware

209-Voice Talent

729-VoiceXML

258-VoIP

122-Volusion

568-VPS

299-vTiger

786-Vuforia

766-WatchKit

564-Web Hosting

95-Web Scraping

199-Web Search

33-Web Security

687-Web Services

487-webMethods

490-WebOS

17-Website Design

454-Website Management

219-Website Testing

228-Weddings

876-Weebly

516-Welsh

567-WHMCS

342-WIKI

605-Wikipedia

604-Windows 8

510-Windows API

296-Windows CE

29-Windows Desktop

140-Windows Mobile

489-Windows Phone

358-Windows Server

41-Wireless

873-Wix

666-Wolfram

788-WooCommerce

197-Word

670-Word Processing

69-Wordpress

855-Workshops

426-WPF

783-Wufoo

508-x86/x64 Assembler

739-Xero

10-XML

629-XMPP

803-Xoops

702-XQuery

345-XSLT

88-XXX

416-Yahoo! Store Design

862-Yard Work &amp; Removal

778-Yarn

402-Yii

152-YouTube

205-Zen Cart

236-Zend

765-Zendesk

566-Zoho';

        $list_category = explode("\n", $list_category);
        $db =& JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('project_title')
            ->from('#__jblance_project');
        $list_title_project = $db->setQuery($query)->loadColumn();
        foreach ($list_category as $category) {
            $category = trim($category);
            if ($category == '')
                continue;
            $category = explode('-', $category);

            $query = $db->getQuery(true);
            $query->select('id')
                ->from('#__jblance_category')
                ->where('category=' . $query->q($category[1]));
            $cat_id = $db->setQuery($query)->loadResult();

            $link = 'https://www.freelancer.com/ajax/table/project_contest_datatable.php?iDisplayLength=1000&status=open&skills_chosen=' . $category[0];
            $data = JUtility::getCurl($link);
            $data = json_decode($data);

            foreach ($data->aaData as $item) {
                $app =& JFactory::getApplication();

                $row =& JTable::getInstance('project', 'Table');
                $id = $app->input->get('id', 0, 'int');
                $post = JRequest::get('post');
                $post['project_title'] = $item[1];
                if (in_array($post['project_title'], $list_title_project))
                    continue;
                $post['description'] = $item[2];
                $budget = $item[32];
                $params = new JRegistry;
                $params->set('item_freelancer.project_id', $item[0]);
                $post['params'] = $params->toString();

                $budgetRange = "$budget->minbudget_usd-$budget->maxbudget_usd";
                $now = JFactory::getDate();
                $post['create_date'] = $now->toSql();


                $post['id_category'] = $cat_id;


                $budgetRange = explode('-', $budgetRange);
                $post['budgetmin'] = $budgetRange[0];
                $post['budgetmax'] = $budgetRange[1];
                if (!$row->save($post)) {
                    JError::raiseError(500, $row->getError());
                }
                $list_title_project[] = $post['project_title'];


            }


        }
        $link = 'https://www.freelancer.com/ajax/table/project_contest_datatable.php?iDisplayLength=1000&status=open&skills_chosen=100';
        $data = JUtility::getCurl($link);
        $data = json_decode($data);
        $db =& JFactory::getDBO();
        foreach ($data->aaData as $item) {
            $app =& JFactory::getApplication();

            $row =& JTable::getInstance('project', 'Table');
            $id = $app->input->get('id', 0, 'int');
            $post = JRequest::get('post');
            $post['project_title'] = $item[1];
            $post['description'] = $item[2];
            $budget = $item[32];
            $budgetRange = "$budget->minbudget_usd-$budget->maxbudget_usd";
            $now = JFactory::getDate();
            $post['create_date'] = $now->toSql();


            $post['id_category'] = 0;


            $budgetRange = explode('-', $budgetRange);
            $post['budgetmin'] = $budgetRange[0];
            $post['budgetmax'] = $budgetRange[1];

            if (!$row->save($post)) {
                JError::raiseError(500, $row->getError());
            }


        }
    }

    function getShowMyProject()
    {
        $app =& JFactory::getApplication();
        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();

        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->input->get('limitstart', 0, 'int');

        $query = 'SELECT * FROM #__jblance_project p WHERE p.publisher_userid=' . $user->id . ' ORDER BY p.id DESC';
        $db->setQuery($query);
        $db->execute();
        $total = $db->getNumRows();

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        $return[1] = $pageNav;
        return $return;
    }

    function getListProject()
    {
        $app =& JFactory::getApplication();
        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();
        $now =& JFactory::getDate();
        $where = array();

        // Load the parameters.
        $params = $app->getParams();
        $param_status = $params->get('param_status', 'all');
        $param_upgrade = $params->get('param_upgrade', 'all');

        if ($param_status == 'open')
            $where[] = "p.status=" . $db->quote('COM_JBLANCE_OPEN');
        elseif ($param_status == 'frozen')
            $where[] = "p.status=" . $db->quote('COM_JBLANCE_FROZEN');
        elseif ($param_status == 'closed')
            $where[] = "p.status=" . $db->quote('COM_JBLANCE_CLOSED');

        if ($param_upgrade == 'featured')
            $where[] = "p.is_featured=1";
        elseif ($param_upgrade == 'urgent')
            $where[] = "p.is_urgent=1";
        elseif ($param_upgrade == 'private')
            $where[] = "p.is_private=1";
        elseif ($param_upgrade == 'sealed')
            $where[] = "p.is_sealed=1";


        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->input->get('limitstart', 0, 'int');


        $where[] = "p.approved=1";
        $where[] = "'$now' > p.start_date";

        $where = (count($where) ? ' WHERE (' . implode(') AND (', $where) . ')' : '');

        $query = "SELECT p.*,(TO_DAYS(p.start_date) - TO_DAYS(NOW())) AS daydiff FROM #__jblance_project p " .
            $where . " " .
            "ORDER BY p.is_featured DESC, p.id DESC";//echo $query;
        $db->setQuery($query);
        $db->execute();
        $total = $db->getNumRows();

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        $return[1] = $pageNav;
        return $return;
    }

    function getDetailProject()
    {
        $app =& JFactory::getApplication();
        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();
        $id = $app->input->get('id', 0, 'int');

        $row =& JTable::getInstance('project', 'Table');
        $row->load($id);

        //redirect the project to login page if the project is a `private` project and user is not logged in
        if ($row->is_private && $user->guest) {
            $url = JFactory::getURI()->toString();
            $msg = JText::_('COM_JBLANCE_PRIVATE_PROJECT_LOGGED_IN_TO_SEE_DESCRIPTION');
            $link_login = JRoute::_('index.php?option=com_users&view=login&return=' . base64_encode($url), false);
            $app->redirect($link_login, $msg);
        }

        //redirect the user to dashboard if the project is not approved.
        if (!$row->approved) {
            $msg = JText::_('COM_JBLANCE_PROJECT_PENDING_APPROVAL_FROM_ADMIN');
            $link_dash = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
            $app->redirect($link_dash, $msg, 'error');
        }

        //get project files
        $query = 'SELECT * FROM #__jblance_project_file WHERE project_id=' . $id;
        $db->setQuery($query);
        $projfiles = $db->loadObjectList();

        //if the project is sealed, get the particular bid row for the bidder.
        $projHelper = JblanceHelper::get('helper.project');        // create an instance of the class ProjectHelper
        $hasBid = $projHelper->hasBid($row->id, $user->id);

        $bidderQuery = 'TRUE';
        if ($row->is_sealed && $hasBid) {
            $bidderQuery = " b.user_id=$user->id";
        }

        //for nda projects, bid count should inlcude only signed bids
        $ndaQuery = 'TRUE';
        if ($row->is_nda)
            $ndaQuery = " b.is_nda_signed=1";

        //get bid info
        $query = "SELECT b.*, u.username FROM #__jblance_bid b " .
            "INNER JOIN #__users u ON b.user_id=u.id " .
            "WHERE b.project_id =" . $id . " AND $bidderQuery AND $ndaQuery";//echo $query;
        $db->setQuery($query);
        $bids = $db->loadObjectList();

        $query = "SELECT * FROM #__jblance_custom_field " .
            "WHERE published=1 AND field_for=" . $db->quote('project') . " " .
            "ORDER BY ordering";
        $db->setQuery($query);
        $fields = $db->loadObjectList();

        //get the forum list
        $query = "SELECT * FROM #__jblance_forum " .
            "WHERE project_id=$row->id " .
            "ORDER BY date_post ASC";
        $db->setQuery($query);//echo $query;
        $forums = $db->loadObjectList();

        $return[0] = $row;
        $return[1] = $projfiles;
        $return[2] = $bids;
        $return[3] = $fields;
        $return[4] = $forums;
        return $return;
    }

    function getPlaceBid()
    {
        $app =& JFactory::getApplication();
        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();
        $id = $app->input->get('id', 0, 'int');    //id is the "project id"

        $project =& JTable::getInstance('project', 'Table');
        $project->load($id);

        $config =& JblanceHelper::getConfig();
        $currencysym = $config->currencySymbol;

        //project in Frozen/Closed should not be allowed to bid
        if ($project->status != 'COM_JBLANCE_OPEN') {
            $link = JRoute::_('index.php?option=com_jblance&view=project&layout=listproject', false);
            $app->redirect($link);
            return;
        }

        //get the bid id
        $query = "SELECT id FROM #__jblance_bid WHERE project_id=" . $id . " AND user_id=" . $user->id;
        $db->setQuery($query);
        $bid_id = $db->loadResult();

        $bid =& JTable::getInstance('bid', 'Table');
        $bid->load($bid_id);

        //check if the user's plan is expired or not approved. If so, do not allow him to bid new on project
        $planStatus = JblanceHelper::planStatus($user->id);
        if (empty($bid_id) && ($planStatus == 1 || $planStatus == 2)) {
            $msg = JText::sprintf('COM_JBLANCE_NOT_ALLOWED_TO_DO_OPERATION_NO_ACTIVE_SUBSCRIPTION');
            $link = JRoute::_('index.php?option=com_jblance&view=user&layout=dashboard', false);
            $app->redirect($link, $msg, 'error');
            return false;
        }

        //check if the user has enough fund to bid new on projects. This should be checked for new bids only
        $plan = JblanceHelper::whichPlan($user->id);
        $chargePerBid = $plan->flChargePerBid;

        if (($chargePerBid > 0) && (empty($bid_id))) {    // bid_id will be empty for new bids
            $totalFund = JblanceHelper::getTotalFund($user->id);
            if ($totalFund < $chargePerBid) {
                $msg = JText::sprintf('COM_JBLANCE_BALANCE_INSUFFICIENT_TO_BID_PROJECT', $currencysym, $chargePerBid);
                $link = JRoute::_('index.php?option=com_jblance&view=membership&layout=depositfund', false);
                $app->redirect($link, $msg, 'error');
                return false;
            }
        }

        $return[0] = $project;
        $return[1] = $bid;
        return $return;
    }

    function getShowMyBid()
    {
        $db =& JFactory::getDBO();
        $user =& JFactory::getUser();

        $query = "SELECT b.*,p.id proj_id,p.project_title,p.status proj_status,p.assigned_userid,p.publisher_userid,p.paid_amt FROM #__jblance_bid b " .
            "LEFT JOIN #__jblance_project p ON b.project_id=p.id " .
            "WHERE user_id =" . $user->id;//echo $query;
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        return $return;
    }

    function getPickUser()
    {

        $app =& JFactory::getApplication();
        $db =& JFactory::getDBO();
        $id = $app->input->get('id', 0, 'int');    //proj id

        $project =& JTable::getInstance('project', 'Table');
        $project->load($id);

        $query = "SELECT b.*,u.username,p.project_title FROM #__jblance_bid b " .
            "LEFT JOIN #__jblance_project p ON b.project_id=p.id " .
            "INNER JOIN #__users u ON b.user_id=u.id " .
            //"WHERE b.project_id =".$id." AND b.status =''";
            "WHERE b.project_id =" . $id . " AND TRUE";
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        $return[1] = $project;
        return $return;
    }

    function getRateUser()
    {
        $app =& JFactory::getApplication();
        $db =& JFactory::getDBO();
        $id = $app->input->get('id', 0, 'int');    //rate id

        $rate =& JTable::getInstance('rating', 'Table');
        $rate->load($id);

        //get info project
        $project =& JTable::getInstance('project', 'Table');
        $project->load($rate->project_id);

        $return[0] = $rate;
        $return[1] = $project;
        return $return;
    }

    //7.Search Project
    function getSearchProject()
    {

        // Initialize variables
        $app =& JFactory::getApplication();
        $user =& JFactory::getUser();
        $db =& JFactory::getDBO();
        $now =& JFactory::getDate();

        $keyword = $app->input->get('keyword', '', 'string');
        $phrase = $app->input->get('phrase', 'any', 'string');
        $id_categ = $app->input->get('id_categ', array(), 'array');
        $min_budget = $app->input->get('min_bud', '', 'string');
        $max_budget = $app->input->get('max_bud', '', 'string');
        $status = $app->input->get('status', '', 'string');

        $keyword = preg_replace("/\s*,\s*/", ",", $keyword); //remove the spaces before and after the commas(,)
        switch ($phrase) {
            case 'exact':
                $text = $db->quote('%' . $db->escape($keyword, true) . '%', false);
                $wheres2 = array();
                $wheres2[] = 'p.project_title LIKE ' . $text;
                $wheres2[] = 'ju.biz_name LIKE ' . $text;
                $wheres2[] = 'cv.value LIKE ' . $text;
                $wheres2[] = 'cv.valuetext LIKE ' . $text;
                $wheres2[] = 'p.description LIKE ' . $text;
                $queryStrings[] = '(' . implode(') OR (', $wheres2) . ')';
                break;

            case 'all':
            case 'any':
            default:
                $words = explode(',', $keyword);
                $wheres = array();
                foreach ($words as $word) {
                    $word = $db->quote('%' . $db->escape($word, true) . '%', false);
                    $wheres2 = array();
                    $wheres2[] = 'p.project_title LIKE ' . $word;
                    $wheres2[] = 'ju.biz_name LIKE ' . $word;
                    $wheres2[] = 'cv.value LIKE ' . $word;
                    $wheres2[] = 'cv.valuetext LIKE ' . $word;
                    $wheres2[] = 'p.description LIKE ' . $word;
                    $wheres[] = implode(' OR ', $wheres2);
                }
                $queryStrings[] = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
                break;
        }

        if (count($id_categ) > 0 && !(count($id_categ) == 1 && empty($id_categ[0]))) {
            if (is_array($id_categ)) {
                $miniquery = array();
                foreach ($id_categ as $cat) {
                    $miniquery[] = "FIND_IN_SET($cat, p.id_category)";
                }
                $querytemp = '(' . implode(' OR ', $miniquery) . ')';
            }
            $queryStrings[] = $querytemp;
        }
        if ($min_budget > 0) {
            $queryStrings[] = "p.budgetmin >= " . $db->quote($min_budget);
        }
        if ($max_budget > 0) {
            $queryStrings[] = "p.budgetmax <= " . $db->quote($max_budget);
        }
        if ($status != '') {
            $queryStrings[] = "p.status=" . $db->quote($status);
        }

        $queryStrings[] = "p.approved=1";
        $queryStrings[] = "'$now' > p.start_date ";

        $where = implode(' AND ', $queryStrings);

        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->input->get('limitstart', 0, 'int');

        $query = "SELECT DISTINCT p.*,(TO_DAYS(p.start_date) - TO_DAYS(NOW())) AS daydiff FROM #__jblance_project p" .
            " LEFT JOIN #__jblance_user ju ON p.publisher_userid = ju.user_id" .
            " LEFT JOIN #__jblance_custom_field_value cv ON cv.projectid=p.id" .
            " WHERE " . $where .
            " ORDER BY p.id DESC";
        $db->setQuery($query);//echo $query;
        $db->execute();
        $total = $db->getNumRows();

        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
        $rows = $db->loadObjectList();

        $return[0] = $rows;
        $return[1] = $pageNav;
        return $return;
    }

    /* Misc Functions */
    function countBids($id)
    {
        $db =& JFactory::getDBO();
        $row =& JTable::getInstance('project', 'Table');
        $row->load($id);

        //for nda projects, bid count should include only signed bids
        $ndaQuery = 'TRUE';
        if ($row->is_nda)
            $ndaQuery = "is_nda_signed=1";

        $query = "SELECT COUNT(*) FROM #__jblance_bid WHERE project_id = $id AND $ndaQuery";
        $db->setQuery($query);
        $total = $db->loadResult();
        return $total;
    }

    function getRate($pid, $userid)
    {
        $db =& JFactory::getDBO();
        $query = "SELECT id,quality_clarity FROM #__jblance_rating WHERE project_id = " . $pid . " AND target =" . $userid;
        $db->setQuery($query);
        $rate = $db->loadObject();
        return $rate;
    }

    function getBidInfo($pid, $userid)
    {
        $db =& JFactory::getDBO();
        $query = "SELECT amount AS bidamount, status FROM #__jblance_bid WHERE project_id = " . $pid . " AND user_id =" . $userid;
        $db->setQuery($query);
        $bidInfo = $db->loadObject();
        return $bidInfo;
    }

    function getSelectRating($var, $default)
    {
        $put[] = JHTML::_('select.option', '', '- ' . JText::_('COM_JBLANCE_PLEASE_SELECT') . ' -');
        $put[] = JHTML::_('select.option', 1, '1 --- ' . JText::_('COM_JBLANCE_VERY_POOR'));
        $put[] = JHTML::_('select.option', 2, '2');
        $put[] = JHTML::_('select.option', 3, '3 --- ' . JText::_('COM_JBLANCE_ACCEPTABLE'));
        $put[] = JHTML::_('select.option', 4, '4');
        $put[] = JHTML::_('select.option', 5, '5 --- ' . JText::_('COM_JBLANCE_EXCELLENT'));
        $rating = JHTML::_('select.genericlist', $put, $var, "class='required'", 'value', 'text', $default);
        return $rating;
    }


}