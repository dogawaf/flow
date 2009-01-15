<?php
declare(ENCODING = 'utf-8');
namespace F3\FLOW3\AOP;

/*                                                                        *
 * This script belongs to the FLOW3 framework.                            *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License as published by the *
 * Free Software Foundation, either version 3 of the License, or (at your *
 * option) any later version.                                             *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Lesser       *
 * General Public License for more details.                               *
 *                                                                        *
 * You should have received a copy of the GNU Lesser General Public       *
 * License along with the script.                                         *
 * If not, see http://www.gnu.org/licenses/lgpl.html                      *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * @package FLOW3
 * @subpackage AOP
 * @version $Id$
 */

/**
 * Testcase for the Pointcut Filter
 *
 * @package FLOW3
 * @subpackage AOP
 * @version $Id$
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser Public License, version 3 or later
 */
class PointcutFilterTest extends \F3\Testing\BaseTestCase {


	/**
	 * @test
	 * @expectedException \F3\FLOW3\AOP\Exception\UnknownPointcut
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function matchesThrowsAnExceptionIfTheSpecifiedPointcutDoesNotExist() {
		$className = 'Foo';
		$methodName = 'bar';
		$methodDeclaringClassName = 'Baz';
		$pointcutQueryIdentifier = 42;

		$mockAOPFramework = $this->getMock('F3\FLOW3\AOP\Framework', array('findPointcut'), array(), '', FALSE);
		$mockAOPFramework->expects($this->once())->method('findPointcut')->with('Aspect', 'pointcut')->will($this->returnValue(FALSE));

		$pointcutFilter = new \F3\FLOW3\AOP\PointcutFilter('Aspect', 'pointcut');
		$pointcutFilter->injectAOPFramework($mockAOPFramework);
		$pointcutFilter->matches($className, $methodName, $methodDeclaringClassName, $pointcutQueryIdentifier);
	}

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function matchesTellsIfTheSpecifiedRegularExpressionMatchesTheGivenClassName() {
		$className = 'Foo';
		$methodName = 'bar';
		$methodDeclaringClassName = 'Baz';
		$pointcutQueryIdentifier = 42;

		$mockPointcut = $this->getMock('F3\FLOW3\AOP\Pointcut', array('matches'), array(), '', FALSE);
		$mockPointcut->expects($this->once())->method('matches')->with($className, $methodName, $methodDeclaringClassName, $pointcutQueryIdentifier)->will($this->returnValue('the result'));

		$mockAOPFramework = $this->getMock('F3\FLOW3\AOP\Framework', array('findPointcut'), array(), '', FALSE);
		$mockAOPFramework->expects($this->once())->method('findPointcut')->with('Aspect', 'pointcut')->will($this->returnValue($mockPointcut));

		$pointcutFilter = new \F3\FLOW3\AOP\PointcutFilter('Aspect', 'pointcut');
		$pointcutFilter->injectAOPFramework($mockAOPFramework);
		$this->assertSame('the result', $pointcutFilter->matches($className, $methodName, $methodDeclaringClassName, $pointcutQueryIdentifier));
	}
}
?>